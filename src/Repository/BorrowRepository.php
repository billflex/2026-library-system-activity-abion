<?php

declare(strict_types=1);

namespace App\Repository;

use App\Config\DatabaseConnection;
use App\Config\LibraryConfig;
use App\Entity\BorrowRecord;
use App\Exception\DatabaseException;
use DateTimeImmutable;

/**
 * Handles borrow and return database operations.
 */
class BorrowRepository
{
    private DatabaseConnection $connection;

    public function __construct(DatabaseConnection $connection)
    {
        $this->connection = $connection;
    }

    public function borrowBook(BorrowRecord $record): int
    {
        $sql = 'INSERT INTO borrow_records (student_id, book_id, borrow_date, due_date, status) VALUES (?, ?, ?, ?, ?)';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param(
            'iisss',
            $record->getStudentId(),
            $record->getBookId(),
            $record->getBorrowDate()->format('Y-m-d'),
            $record->getDueDate()->format('Y-m-d'),
            $record->getStatus()
        );
        $statement->execute();

        if ($statement->affected_rows < 1) {
            throw new DatabaseException('Failed to create borrow record.');
        }

        return $statement->insert_id;
    }

    public function findById(int $recordId): ?BorrowRecord
    {
        $sql = 'SELECT * FROM borrow_records WHERE record_id = ?';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('i', $recordId);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();

        if ($data === null) {
            return null;
        }

        return BorrowRecord::fromArray($data);
    }

    public function returnBook(int $recordId, DateTimeImmutable $returnDate, float $fineAmount): float
    {
        $sql = 'UPDATE borrow_records SET return_date = ?, fine_amount = ?, status = ? WHERE record_id = ?';
        $status = LibraryConfig::STATUS_RETURNED;
        $statement = $this->connection->prepare($sql);
        $statement->bind_param(
            'sdsi',
            $returnDate->format('Y-m-d'),
            $fineAmount,
            $status,
            $recordId
        );
        $statement->execute();

        if ($statement->affected_rows < 1) {
            throw new DatabaseException('Failed to update return record.');
        }

        return $fineAmount;
    }

    public function findOverdueBooks(): array
    {
        $sql = 'SELECT br.*, b.title, s.name FROM borrow_records br JOIN books b ON br.book_id = b.book_id JOIN students s ON br.student_id = s.student_id WHERE br.due_date < ? AND br.status = ?';
        $date = (new DateTimeImmutable('today'))->format('Y-m-d');
        $status = LibraryConfig::STATUS_BORROWED;

        $statement = $this->connection->prepare($sql);
        $statement->bind_param('ss', $date, $status);
        $statement->execute();
        $result = $statement->get_result();

        $records = [];

        while (($data = $result->fetch_assoc()) !== null) {
            $records[] = $data;
        }

        return $records;
    }

    public function countBorrowed(): int
    {
        return $this->countByStatus(LibraryConfig::STATUS_BORROWED);
    }

    public function countReturned(): int
    {
        return $this->countByStatus(LibraryConfig::STATUS_RETURNED);
    }

    public function sumFines(): float
    {
        $sql = 'SELECT SUM(fine_amount) AS total FROM borrow_records WHERE fine_amount > 0';
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();

        return $data['total'] !== null ? (float) $data['total'] : 0.0;
    }

    public function countBooks(): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM books';
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();

        return (int) ($data['total'] ?? 0);
    }

    private function countByStatus(string $status): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM borrow_records WHERE status = ?';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('s', $status);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();

        return (int) ($data['total'] ?? 0);
    }
}

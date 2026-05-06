<?php

declare(strict_types=1);

namespace App\Service;

use App\Config\LibraryConfig;
use App\Entity\BorrowRecord;
use App\Exception\ValidationException;
use App\Repository\BookRepository;
use App\Repository\BorrowRepository;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * Contains business logic for borrowing and returning books.
 */
class LibraryService
{
    private BookRepository $bookRepository;

    private BorrowRepository $borrowRepository;

    private float $dailyFineRate;

    public function __construct(BookRepository $bookRepository, BorrowRepository $borrowRepository, float $dailyFineRate)
    {
        $this->bookRepository = $bookRepository;
        $this->borrowRepository = $borrowRepository;
        $this->dailyFineRate = $dailyFineRate;
    }

    public function borrowBook(int $studentId, int $bookId, int $borrowDays): int
    {
        $this->validateStudentId($studentId);
        $this->validateBookId($bookId);
        $this->validateBorrowDays($borrowDays);

        $borrowDate = new DateTimeImmutable('today');
        $dueDate = $this->calculateDueDate($borrowDays);

        $record = new BorrowRecord(
            $studentId,
            $bookId,
            $borrowDate,
            $dueDate,
            LibraryConfig::STATUS_BORROWED
        );

        return $this->borrowRepository->borrowBook($record);
    }

    public function returnBook(int $recordId): float
    {
        $record = $this->borrowRepository->findById($recordId);

        if ($record === null) {
            throw new ValidationException('Borrow record not found.');
        }

        if ($record->getStatus() === LibraryConfig::STATUS_RETURNED) {
            throw new ValidationException('This record is already returned.');
        }

        $returnDate = new DateTimeImmutable('today');
        $fine = $this->calculateOverdueFine($record->getDueDate(), $returnDate);

        return $this->borrowRepository->returnBook($recordId, $returnDate, $fine);
    }

    public function calculateDueDate(int $borrowDays): DateTimeImmutable
    {
        return (new DateTimeImmutable('today'))->modify('+' . $borrowDays . ' days');
    }

    public function calculateOverdueFine(DateTimeInterface $dueDate, DateTimeInterface $returnDate): float
    {
        $interval = $dueDate->diff($returnDate);
        $daysOverdue = (int) $interval->format('%r%a');

        return $daysOverdue > 0 ? $daysOverdue * $this->dailyFineRate : 0.0;
    }

    public function getOverdueBooks(): array
    {
        return $this->borrowRepository->findOverdueBooks();
    }

    private function validateStudentId(int $studentId): void
    {
        if ($studentId <= 0) {
            throw new ValidationException('Student ID must be a positive integer.');
        }
    }

    private function validateBookId(int $bookId): void
    {
        if ($bookId <= 0) {
            throw new ValidationException('Book ID must be a positive integer.');
        }

        if ($this->bookRepository->findById($bookId) === null) {
            throw new ValidationException('Book not found for the provided ID.');
        }
    }

    private function validateBorrowDays(int $borrowDays): void
    {
        if ($borrowDays < 1 || $borrowDays > 60) {
            throw new ValidationException('Borrow duration must be between 1 and 60 days.');
        }
    }
}

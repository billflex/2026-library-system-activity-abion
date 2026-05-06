<?php

declare(strict_types=1);

namespace App\Repository;

use App\Config\DatabaseConnection;
use App\Entity\Book;
use App\Exception\DatabaseException;
/**
 * Handles all book database operations.
 */
class BookRepository
{
    private DatabaseConnection $connection;

    public function __construct(DatabaseConnection $connection)
    {
        $this->connection = $connection;
    }

    public function addBook(Book $book): int
    {
        $sql = 'INSERT INTO books (title, author, year, genre) VALUES (?, ?, ?, ?)';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param(
            'ssis',
            $book->getTitle(),
            $book->getAuthor(),
            $book->getYear(),
            $book->getGenre()
        );
        $statement->execute();

        if ($statement->affected_rows < 1) {
            throw new DatabaseException('Failed to insert new book.');
        }

        return $statement->insert_id;
    }

    public function findById(int $bookId): ?Book
    {
        $sql = 'SELECT * FROM books WHERE book_id = ?';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('i', $bookId);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();

        if ($data === null) {
            return null;
        }

        return Book::fromArray($data);
    }

    public function findAll(): array
    {
        $sql = 'SELECT * FROM books';
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $result = $statement->get_result();

        $books = [];

        while (($data = $result->fetch_assoc()) !== null) {
            $books[] = Book::fromArray($data);
        }

        return $books;
    }

    public function searchByKeyword(string $keyword): array
    {
        $sql = 'SELECT * FROM books WHERE title LIKE ? OR author LIKE ?';
        $pattern = '%' . $keyword . '%';

        $statement = $this->connection->prepare($sql);
        $statement->bind_param('ss', $pattern, $pattern);
        $statement->execute();
        $result = $statement->get_result();

        $books = [];

        while (($data = $result->fetch_assoc()) !== null) {
            $books[] = Book::fromArray($data);
        }

        return $books;
    }
}

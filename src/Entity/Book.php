<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Represents a single book in the library.
 */
class Book
{
    private ?int $id;

    private string $title;

    private string $author;

    private int $year;

    private string $genre;

    public function __construct(string $title, string $author, int $year, string $genre, ?int $id = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->genre = $genre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public static function fromArray(array $data): self
    {
        $book = new self(
            (string) ($data['title'] ?? ''),
            (string) ($data['author'] ?? ''),
            (int) ($data['year'] ?? 0),
            (string) ($data['genre'] ?? '')
        );

        if (isset($data['book_id'])) {
            $book->setId((int) $data['book_id']);
        }

        return $book;
    }
}

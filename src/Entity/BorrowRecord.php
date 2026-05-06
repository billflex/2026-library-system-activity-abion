<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Represents a borrow transaction record.
 */
class BorrowRecord
{
    private ?int $id;

    private int $studentId;

    private int $bookId;

    private DateTimeImmutable $borrowDate;

    private DateTimeImmutable $dueDate;

    private ?DateTimeImmutable $returnDate;

    private float $fineAmount;

    private string $status;

    public function __construct(
        int $studentId,
        int $bookId,
        DateTimeImmutable $borrowDate,
        DateTimeImmutable $dueDate,
        string $status,
        ?int $id = null,
        ?DateTimeImmutable $returnDate = null,
        float $fineAmount = 0.0
    ) {
        $this->id = $id;
        $this->studentId = $studentId;
        $this->bookId = $bookId;
        $this->borrowDate = $borrowDate;
        $this->dueDate = $dueDate;
        $this->status = $status;
        $this->returnDate = $returnDate;
        $this->fineAmount = $fineAmount;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentId(): int
    {
        return $this->studentId;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getBorrowDate(): DateTimeImmutable
    {
        return $this->borrowDate;
    }

    public function getDueDate(): DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function getReturnDate(): ?DateTimeImmutable
    {
        return $this->returnDate;
    }

    public function setReturnDate(DateTimeImmutable $returnDate): void
    {
        $this->returnDate = $returnDate;
    }

    public function getFineAmount(): float
    {
        return $this->fineAmount;
    }

    public function setFineAmount(float $fineAmount): void
    {
        $this->fineAmount = $fineAmount;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public static function fromArray(array $data): self
    {
        $borrowDate = new DateTimeImmutable((string) ($data['borrow_date'] ?? 'now'));
        $dueDate = new DateTimeImmutable((string) ($data['due_date'] ?? 'now'));
        $returnDate = null;

        if (!empty($data['return_date'])) {
            $returnDate = new DateTimeImmutable((string) $data['return_date']);
        }

        return new self(
            (int) ($data['student_id'] ?? 0),
            (int) ($data['book_id'] ?? 0),
            $borrowDate,
            $dueDate,
            (string) ($data['status'] ?? ''),
            $data['record_id'] ?? null,
            $returnDate,
            (float) ($data['fine_amount'] ?? 0.0)
        );
    }
}

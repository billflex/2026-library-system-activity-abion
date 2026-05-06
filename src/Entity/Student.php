<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Represents a library student.
 */
class Student
{
    private ?int $id;

    private string $name;

    public function __construct(string $name, ?int $id = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string) ($data['name'] ?? ''),
            $data['student_id'] ?? null
        );
    }
}

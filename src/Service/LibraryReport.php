<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\BookRepository;
use App\Repository\BorrowRepository;

/**
 * Generates summary statistics for the library.
 */
class LibraryReport
{
    private BookRepository $bookRepository;

    private BorrowRepository $borrowRepository;

    public function __construct(BookRepository $bookRepository, BorrowRepository $borrowRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->borrowRepository = $borrowRepository;
    }

    public function generate(): array
    {
        return [
            'totalBooks' => $this->borrowRepository->countBooks(),
            'totalBorrowed' => $this->borrowRepository->countBorrowed(),
            'totalReturned' => $this->borrowRepository->countReturned(),
            'totalFines' => $this->borrowRepository->sumFines(),
        ];
    }
}

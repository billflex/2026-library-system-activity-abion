<?php

declare(strict_types=1);

use App\Config\DatabaseConfig;
use App\Config\DatabaseConnection;
use App\Config\LibraryConfig;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\BorrowRepository;
use App\Service\LibraryReport;
use App\Service\LibraryService;
use Throwable;

require_once __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', '1');
error_reporting(E_ALL);

$databaseConfig = new DatabaseConfig();
$connection = new DatabaseConnection($databaseConfig);
$bookRepository = new BookRepository($connection);
$borrowRepository = new BorrowRepository($connection);
$libraryService = new LibraryService($bookRepository, $borrowRepository, LibraryConfig::DAILY_FINE_RATE);
$libraryReport = new LibraryReport($bookRepository, $borrowRepository);

$action = $_GET['act'] ?? 'list';
$message = '';
$books = [];
$reportData = [];

try {
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim((string) ($_POST['title'] ?? ''));
        $author = trim((string) ($_POST['author'] ?? ''));
        $year = (int) ($_POST['year'] ?? 0);
        $genre = trim((string) ($_POST['genre'] ?? ''));

        $book = new Book($title, $author, $year, $genre);
        $bookId = $bookRepository->addBook($book);

        $message = sprintf('Book added successfully with ID %d.', $bookId);
        $action = 'list';
    }

    if ($action === 'search') {
        $keyword = trim((string) ($_GET['q'] ?? ''));
        $books = $bookRepository->searchByKeyword($keyword);
    }

    if ($action === 'borrow' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $studentId = (int) ($_POST['student_id'] ?? 0);
        $bookId = (int) ($_POST['book_id'] ?? 0);
        $borrowDays = (int) ($_POST['borrow_days'] ?? LibraryConfig::DEFAULT_BORROW_DAYS);

        $recordId = $libraryService->borrowBook($studentId, $bookId, $borrowDays);
        $message = sprintf('Borrow record created successfully with ID %d.', $recordId);
        $action = 'list';
    }

    if ($action === 'return' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $recordId = (int) ($_POST['record_id'] ?? 0);
        $fine = $libraryService->returnBook($recordId);
        $message = sprintf('Book returned. Fine amount: $%.2f.', $fine);
        $action = 'list';
    }

    if ($action === 'report') {
        $reportData = $libraryReport->generate();
    }

    if ($action === 'list') {
        $books = $bookRepository->findAll();
    }
} catch (Throwable $exception) {
    $message = $exception->getMessage();
}

if ($action === 'report') {
    include __DIR__ . '/../src/View/report_view.php';
    return;
}

if ($action === 'borrow') {
    include __DIR__ . '/../src/View/borrow_form.php';
    return;
}

include __DIR__ . '/../src/View/book_list.php';

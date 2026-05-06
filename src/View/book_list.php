<?php

declare(strict_types=1);

/**
 * Renders the list of books and the add/search controls.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 24px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 16px;
        }

        th,
        td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .message {
            margin: 12px 0;
            padding: 12px;
            background-color: #e7f7e7;
            border: 1px solid #9cd3a8;
        }

        .actions {
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <h1>Library Book List</h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="actions">
        <form method="get" action="index.php" style="display: inline-block; margin-right: 16px;">
            <input type="hidden" name="act" value="search">
            <input type="text" name="q" placeholder="Search by title or author" required>
            <button type="submit">Search</button>
        </form>

        <a href="index.php?act=borrow">Borrow Book</a> |
        <a href="index.php?act=report">View Report</a>
    </div>

    <h2>Add New Book</h2>
    <form method="post" action="index.php?act=add">
        <label>
            Title:<br>
            <input type="text" name="title" required>
        </label>
        <br><br>
        <label>
            Author:<br>
            <input type="text" name="author" required>
        </label>
        <br><br>
        <label>
            Year:<br>
            <input type="number" name="year" min="1000" max="2100" required>
        </label>
        <br><br>
        <label>
            Genre:<br>
            <input type="text" name="genre" required>
        </label>
        <br><br>
        <button type="submit">Save Book</button>
    </form>

    <h2>Available Books</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Year</th>
                <th>Genre</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $book->getId(), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($book->getTitle(), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($book->getAuthor(), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $book->getYear(), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($book->getGenre(), ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

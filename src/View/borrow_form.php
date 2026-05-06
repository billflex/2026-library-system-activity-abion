<?php

declare(strict_types=1);

/**
 * Renders the borrow transaction form.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrow Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 24px;
        }

        label {
            display: block;
            margin-bottom: 12px;
        }

        input {
            width: 320px;
            padding: 6px;
        }

        button {
            padding: 8px 16px;
        }
    </style>
</head>
<body>
    <h1>Borrow Book</h1>

    <?php if (!empty($message)): ?>
        <div><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?act=borrow">
        <label>
            Student ID:<br>
            <input type="number" name="student_id" min="1" required>
        </label>

        <label>
            Book ID:<br>
            <input type="number" name="book_id" min="1" required>
        </label>

        <label>
            Borrow Days:<br>
            <input type="number" name="borrow_days" min="1" max="60" value="14">
        </label>

        <button type="submit">Borrow</button>
    </form>

    <p><a href="index.php">Back to book list</a></p>
</body>
</html>

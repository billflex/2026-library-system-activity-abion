<?php

declare(strict_types=1);

/**
 * Renders the library report summary.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 24px;
        }

        .report {
            max-width: 600px;
        }

        .report-item {
            margin-bottom: 12px;
        }

        .report-item span {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Library Report</h1>

    <?php if (!empty($message)): ?>
        <div><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="report">
        <div class="report-item">
            <span>Total Books:</span> <?= htmlspecialchars((string) ($reportData['totalBooks'] ?? 0), ENT_QUOTES, 'UTF-8') ?>
        </div>
        <div class="report-item">
            <span>Borrowed:</span> <?= htmlspecialchars((string) ($reportData['totalBorrowed'] ?? 0), ENT_QUOTES, 'UTF-8') ?>
        </div>
        <div class="report-item">
            <span>Returned:</span> <?= htmlspecialchars((string) ($reportData['totalReturned'] ?? 0), ENT_QUOTES, 'UTF-8') ?>
        </div>
        <div class="report-item">
            <span>Total Fines Collected:</span> $<?= htmlspecialchars(number_format((float) ($reportData['totalFines'] ?? 0.0), 2), ENT_QUOTES, 'UTF-8') ?>
        </div>
    </div>

    <p><a href="index.php">Back to book list</a></p>
</body>
</html>

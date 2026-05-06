<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Application constants used throughout the library system.
 */
class LibraryConfig
{
    public const DEFAULT_BORROW_DAYS = 14;

    public const DAILY_FINE_RATE = 5.0;

    public const STATUS_BORROWED = 'borrowed';

    public const STATUS_RETURNED = 'returned';
}

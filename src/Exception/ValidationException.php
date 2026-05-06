<?php

declare(strict_types=1);

namespace App\Exception;

use InvalidArgumentException;

/**
 * Thrown when a validation error occurs.
 */
class ValidationException extends InvalidArgumentException
{
}

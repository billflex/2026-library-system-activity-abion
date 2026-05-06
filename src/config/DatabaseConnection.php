<?php

declare(strict_types=1);

namespace App\Config;

use App\Exception\DatabaseException;
use mysqli;
use mysqli_stmt;

/**
 * Manages the MySQL database connection.
 */
class DatabaseConnection
{
    private mysqli $connection;

    public function __construct(DatabaseConfig $config)
    {
        $this->connection = new mysqli(
            $config->getHost(),
            $config->getUsername(),
            $config->getPassword(),
            $config->getDatabase()
        );

        if ($this->connection->connect_error) {
            throw new DatabaseException(
                'Database connection failed: ' . $this->connection->connect_error
            );
        }
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }

    public function prepare(string $query): mysqli_stmt
    {
        $statement = $this->connection->prepare($query);

        if ($statement === false) {
            throw new DatabaseException(
                'Failed to prepare SQL statement: ' . $this->connection->error
            );
        }

        return $statement;
    }
}

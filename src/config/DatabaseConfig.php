<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Database configuration class.
 */
class DatabaseConfig
{
    private string $host;
    private string $database;
    private string $username;
    private string $password;
    private string $charset;

    public function __construct(
        string $host = 'localhost',
        string $database = 'library_system',
        string $username = 'root',
        string $password = '',
        string $charset = 'utf8mb4'
    ) {
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->charset = $charset;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function getDsn(): string
    {
        return sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $this->host,
            $this->database,
            $this->charset
        );
    }
}
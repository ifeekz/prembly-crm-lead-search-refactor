<?php

declare(strict_types=1);

namespace Prembly\Crm\DB;

use \PDO;
use \PDOStatement;

/**
 * DB_Common_Functions class provides common database functions for the application.
 */
class DB_Common_Functions
{
    private PDO $pdo;


    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Prepares a SQL statement.
     *
     * @param string $sql The SQL statement to prepare.
     * @return PDOStatement The prepared statement.
     * @throws RuntimeException If the statement fails to prepare.
     */
    public function query(string $sql): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException('Failed to prepare statement: ' . implode(' ', $this->pdo->errorInfo()));
        }
        return $stmt;
    }
}

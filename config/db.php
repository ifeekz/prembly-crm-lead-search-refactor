<?php

declare(strict_types=1);

return [
    // For demo purposes, SQLite is used. Replace with your actual DSN and credentials.
    // Example for MySQL:
    // 'dsn' => 'mysql:host=127.0.0.1;dbname=crm;charset=utf8mb4',
    // 'user' => 'crm_user',
    // 'pass' => 'secret',
    'dsn'  => 'sqlite:' . __DIR__ . '/../storage/crm.sqlite',
    'user' => null,
    'pass' => null,
];

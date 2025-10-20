<?php
declare(strict_types=1);

function db() : PDO
{
    static $pdo = null;

    if ($pdo === null)
    {
        // Update if needed
        $host = 'localhost';
        $port = '3306';
        $name = 'groceries_web_app';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";

        $opts = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $pdo = new PDO($dsn, $user, $pass, $opts);
    }

    return $pdo;
}

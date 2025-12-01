<?php
declare(strict_types=1);

final class Database
{
    /** Adjust if your local MySQL is different */
    private const HOST = '127.0.0.1';
    private const NAME = 'article_aggregator_co';
    private const USER = 'root';
    private const PASS = '';
    

    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                self::HOST,
                self::NAME
            );

            self::$pdo = new PDO(
                $dsn,
                self::USER,
                self::PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        }
        return self::$pdo;
    }
}


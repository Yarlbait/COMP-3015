<?php
declare(strict_types=1);

namespace Repositories;

require_once __DIR__ . '/../Logger/Log.php';

use PDO;
use PDOException;
use Logger\Log;   

class Repository
{
    protected PDO $pdo;
    private string $hostname;
    private string $username;
    private string $databaseName;
    private string $databasePassword;
    private string $charset;
    private string $port;

    public function __construct()
    {
        // In real projects, you'd load these from .env
        $this->hostname         = 'localhost';
        $this->username         = 'root';
        $this->databaseName     = 'posts_web_app';
        $this->databasePassword = '';
        $this->charset          = 'utf8mb4';
        $this->port             = '3306';

        $dsn = "mysql:host={$this->hostname};port={$this->port};dbname={$this->databaseName};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try
        {
            $this->pdo = new PDO($dsn, $this->username, $this->databasePassword, $options);
        }
        catch (PDOException $e)
        {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}

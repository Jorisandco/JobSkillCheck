<?php

namespace classes;

use classes\Poll;
use classes\Users;
use classes\Sessions;
use PDO;
use PDOException;

require_once "Poll.php";
require_once "Users.php";
require_once "Sessions.php";

class DataBase
{
    private string $host;
    private int $port;
    private string $db_name;
    private string $username;
    private string $password;

    protected ?PDO $conn = null;

    public function __construct()
    {
        $envFile = __DIR__ .  '/../.env';

        $env = parse_ini_file($envFile);

        $this->host     = $env['DB_HOST'] ?? 'localhost';
        $this->port     = (int)($env['DB_PORT'] ?? 3306);
        $this->db_name  = $env['DB_NAME'] ?? '';
        $this->username = $env['DB_USER'] ?? '';
        $this->password = $env['DB_PASSWORD'] ?? '';
    }

    public function connect(): PDO
    {
        if ($this->conn !== null) {
            return $this->conn;
        }

        try {
            $connectionString = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";

            $this->conn = new PDO($connectionString, $this->username, $this->password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $this->conn;
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new \RuntimeException("Database connection failed: " . $e->getMessage());
        }
    }

    public function disconnect(): void
    {
        $this->conn = null;
    }

    public function startTransaction(): void
    {
        $this->connect()->beginTransaction();
    }

    public function commit(): void
    {
        if ($this->conn && $this->conn->inTransaction()) {
            $this->conn->commit();
        }
    }

    public function rollback(): void
    {
        if ($this->conn && $this->conn->inTransaction()) {
            $this->conn->rollBack();
        }
    }
}

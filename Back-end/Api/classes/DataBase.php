<?php

namespace classes;

class DataBase
{
    private $host = "localhost";
    private $Port = "3306";
    private $db_name = "my_database";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection()
    {
        // get data from .env file
        $this->host = getenv('DB_HOST') ?: $this->host;
        $this->Port = getenv('DB_PORT') ?: $this->Port;
        $this->db_name = getenv('DB_NAME') ?: $this->db_name;
        $this->username = getenv('DB_USER') ?: $this->username;
        $this->password = getenv('DB_PASSWORD') ?: $this->password;
    }

    public function connect(): ?\PDO
    {
        $this->getConnection();
        $this->conn = null;
        try {
            $this->conn = new \PDO("mysql:host=" . $this->host . ";port=" . $this->Port . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $this->conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->exec("set names utf8");
        } catch (\PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

    public function disconnect():void
    {
        $this->conn = null;
    }

    public function Rollback():void
    {
        if ($this->conn)
            $this->conn->rollBack();
    }

    public function StartTransaction():void
    {
        if ($this->conn)
            $this->conn->beginTransaction();
    }

    public function EndTransaction():void
    {
        if ($this->conn)
            $this->conn->commit();
    }
}
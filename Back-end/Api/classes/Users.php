<?php

namespace classes;

include_once 'DataBase.php';

use classes\DataBase;

class Users extends DataBase
{
    public function GetUserFromSession($session): array|null
    {
        try {
            $this->connect();
            $query = "SELECT * FROM users 
                      JOIN usersessions ON users.idUsers = usersessions.UserID 
                      WHERE usersessions.Session = :session AND usersessions.Expires > NOW()";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'session' => $session
            ]);

            $user = $stmt->fetch();
            $this->disconnect();

            return $user ?: null;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function GetUsers($Email): array
    {
        try {
            $this->connect();

            $query = "SELECT * FROM users where email = :email";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                'email' => $Email
            ]);
            $users = $stmt->fetchAll();

            $this->disconnect();

            return $users;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function CreateUser($Email): int|null
    {
        try {
            $this->connect();

            $query = "INSERT INTO users (email) VALUES (:email)";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                'email' => $Email
            ]);

            $userId = $this->conn->lastInsertId();

            $this->disconnect();

            return $userId;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function DeleteUser($email): bool
    {
        try {
            $this->connect();

            $query = "DELETE FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                'email' => $email
            ]);

            $this->disconnect();

            return true;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
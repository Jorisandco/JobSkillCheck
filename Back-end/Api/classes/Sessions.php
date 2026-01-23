<?php

namespace classes;

use classes\DataBase;

class Sessions extends DataBase
{
    public function GetSessions($UserID): array
    {
        try {
            $this->connect();

            $query = "SELECT * FROM sessions WHERE user_id = :user";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ":user" => $UserID
            ]);
            $sessions = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $this->disconnect();

            return $sessions;
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function CreateSession($UserID, $Token, $Expiry): bool
    {
        try {
            $this->connect();

            $query = "INSERT INTO sessions (user_id, token, expiry) VALUES (:user, :token, :expiry)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ":user" => $UserID,
                ":token" => $Token,
                ":expiry" => $Expiry
            ]);

            $this->disconnect();

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function UpdateSession($UserID, $Expiry): bool
    {
        try {
            $this->connect();

            $query = "UPDATE sessions SET expiry = :expiry WHERE user_id = :user";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ":user" => $UserID,
                ":expiry" => $Expiry
            ]);

            $this->disconnect();

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function DeleteExpiredSession(): bool
    {
        try {
            $this->connect();

            $query = "DELETE FROM sessions WHERE expiry < NOW()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $this->disconnect();

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function DeleteSession($userID): bool
    {
        try {
            $this->connect();

            $query = "DELETE FROM sessions WHERE user_id = :user";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ":user" => $userID
            ]);

            $this->disconnect();

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
}
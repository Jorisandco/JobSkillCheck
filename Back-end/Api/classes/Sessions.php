<?php

namespace classes;

include_once 'DataBase.php';

use classes\DataBase;
use Random\RandomException;

class Sessions extends DataBase
{
    public function GetSessions($UserID): array
    {
        try {
            $this->connect();

            $query = "SELECT * FROM usersessions WHERE UserID = :user";
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

    /**
     * @throws RandomException
     */
    private function CreateSessionToken() : string
    {
        try {
            return bin2hex(random_bytes(32));
        } catch (RandomException $e) {
            throw new RandomException("Failed to generate session token");
        }
    }

    public function CreateSession($UserID, $Expiry): bool | string
    {
        try {
            $this->connect();

            $sessionToken = $this->CreateSessionToken();

            $query = "INSERT INTO usersessions (UserID, Session, Expires) VALUES (:user, :token, :expiry)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ":user" => $UserID,
                ":token" => $sessionToken,
                ":expiry" => $Expiry
            ]);

            $this->disconnect();

            return $sessionToken;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function UpdateSession($UserID, $Expiry): bool
    {
        try {
            $this->connect();

            $query = "UPDATE usersessions SET Expires = :expiry WHERE UserID = :user";
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

            $query = "DELETE FROM usersessions WHERE Expires < NOW()";
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

            $query = "DELETE FROM usersessions WHERE user_id = :user";
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
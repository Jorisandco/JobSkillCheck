<?php

namespace classes;

use classes\DataBase;

class Sessions extends DataBase
{
    public function GetSessions($UserID):array
    {
        try{
            $query = "SELECT * FROM sessions WHERE user_id = :user";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ":user" => $UserID
            ]);
            $sessions = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $sessions;
        }catch (\PDOException $e){
            return [];
        }
    }

    public function CreateSession($UserID, $Token, $Expiry):bool
    {
        try{
            $query = "INSERT INTO sessions (user_id, token, expiry) VALUES (:user, :token, :expiry)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ":user" => $UserID,
                ":token" => $Token,
                ":expiry" => $Expiry
            ]);
            return true;
        }
        catch (\PDOException $e){
            return false;
        }
    }

    public function UpdateSession($UserID, $Expiry)
    {
        try{
            $query = "UPDATE sessions SET expiry = :expiry WHERE user_id = :user";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ":user" => $UserID,
                ":expiry" => $Expiry
            ]);
            return true;
        }
        catch (\PDOException $e){
            return false;
        }
    }

    public function DeleteExpiredSession()
    {
        try{
            $query = "DELETE FROM sessions WHERE expiry < NOW()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return true;
        }
        catch (\PDOException $e){
            return false;
        }
    }

    public function DeleteSession($userID)
    {
        try {
            $query = "DELETE FROM sessions WHERE user_id = :user";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ":user" => $userID
            ]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
}
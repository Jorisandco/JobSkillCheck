<?php

namespace classes;

use classes\DataBase;
use mysql_xdevapi\CrudOperationBindable;

class Poll extends DataBase
{
    public function GetPoll($poll_id)
    {
        try {
            $this->connect();

            $query = "SELECT * FROM polls WHERE id = :poll_id";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                'poll_id' => $poll_id
            ]);
            $poll = $stmt->fetch();

            $this->disconnect();

            return $poll;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function CreatePoll($question, $userID, $expire, $pollAnswers) :bool
    {
        try {
            $this->connect();

            $sql = "INSERT INTO polls (Question, UserID, Expires) VALUES (:question, :user, :expires)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':question' => $question,
                ':options' => $userID,
                ':created_at' => $expire
            ]);

            $sql = "INSERT INTO poll_answers (PollID, Answer, BarColour) VALUES (:poll_id, :answer, :barcolour)";
            $pollID = $this->conn->lastInsertId();
            $stmt = $this->conn->prepare($sql);

            foreach ($pollAnswers as $answer) {
                $stmt->execute([
                    ':poll_id' => $pollID,
                    ':answer' => $answer['answer'],
                    ':barcolour' => $answer['barcolour']
                ]);
            }

            $this->disconnect();

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function UpdatePoll($pollID, $question, $expires) :bool
    {
        try {
            $this->connect();

            $sql = "UPDATE polls SET Question = :question, Expires = :expires WHERE id = :poll_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':poll_id' => $pollID,
                ':question' => $question,
                ':expires' => $expires
            ]);

            $this->disconnect();

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function UpdatePollQuestions($pollID, $pollAnswers) :bool
    {
        try {
            $this->connect();

            $sql = "UPDATE poll_answers SET Answer = :answer, BarColour = :barcolour WHERE idPoll_answers = :poll_id";
            $stmt = $this->conn->prepare($sql);

            foreach ($pollAnswers as $answer) {
                $stmt->execute([
                    ':poll_id' => $pollID,
                    ':answer' => $answer['answer'],
                    ':barcolour' => $answer['barcolour']
                ]);
            }

            $this->disconnect();

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function DeletePoll($pollID) :bool
    {
        try {
            $this->connect();

            $sql = "DELETE FROM polls WHERE idPoll_answers = :poll_id";

            $this->disconnect();

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function VotePoll($userID, $answerID) :bool
    {
        try {
            $this->connect();

            $sql = "INSERT INTO useranswers (USERID, QUESTIONID) VALUES (:user_id, :answer_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $userID,
                ':answer_id' => $answerID
            ]);

            $this->disconnect();

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
}
<?php

namespace classes;

include_once 'DataBase.php';

use classes\DataBase;

class Poll extends DataBase
{
    public function GetPollAnswerCount($questionID): array|null
    {
        try {
            $this->connect();

            $query = "SELECT COUNT(*) AS total_answers FROM useranswers WHERE QUESTIONID = :question_id;";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                'question_id' => $questionID
            ]);

            $results = $stmt->fetchAll();

            $this->disconnect();

            return $results[0];
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function HasUserAnswered($userID, $pollID): bool
    {
        try {
            $this->connect();

            $query = "SELECT COUNT(*) AS answer_count 
                      FROM useranswers 
                      JOIN poll_answers ON useranswers.QUESTIONID = poll_answers.idPoll_answers 
                      WHERE useranswers.USERID = :user_id AND poll_answers.PollID = :poll_id;";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                'user_id' => $userID,
                'poll_id' => $pollID
            ]);

            $result = $stmt->fetch();

            $this->disconnect();

            return $result['answer_count'] > 0;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function GetPoll($poll_id)
    {
        try {
            $this->connect();

            $query = "SELECT Expires, Question FROM polls WHERE idPolls = :poll_id";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                'poll_id' => $poll_id
            ]);
            $poll = $stmt->fetch();

            $query = "SELECT idPoll_answers, Answer, BarColour FROM poll_answers WHERE PollID = :poll_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'poll_id' => $poll_id
            ]);

            $poll['answers'] = $stmt->fetchAll();

            $this->disconnect();

            return $poll;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function CreatePoll($question, $userID, $expire, $pollAnswers): bool|int
    {
        try {
            $this->connect();

            $sql = "INSERT INTO polls (Question, UserID, Expires) VALUES (:question, :user, :expires)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':question' => $question,
                ':user' => $userID,
                ':expires' => $expire
            ]);

            $pollID = $this->conn->lastInsertId();

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

            return $pollID;
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function UpdatePoll($pollID, $question, $expires): bool
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

    public function UpdatePollQuestions($pollID, $pollAnswers): bool
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

    public function DeletePoll($pollID): bool
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

    public function VotePoll($userID, $answerID): bool
    {
        try {
            $this->connect();

            $sql = "SELECT * FROM useranswers WHERE USERID = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $userID,
            ]);

            $existingVote = $stmt->fetch();
            if (!$existingVote) {
                $existingVote = null;
            }
            if ($existingVote !== null && $existingVote["QUESTIONID"] === $answerID) {
                return false;
            }
            if ($existingVote) {
                $sql = "DELETE FROM useranswers WHERE USERID = :user_id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':user_id' => $userID,
                ]);
            }


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
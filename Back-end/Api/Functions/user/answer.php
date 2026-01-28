<?php

use classes\Poll;
use classes\Users;

return function ($data) {
    $Poll = new Poll();
    $Users = new Users();

    $userData = $Users->GetUserFromSession($data["Session"]);

    $result = $Poll->VotePoll($userData["idUsers"], $data["ANSWER_ID"]);

    if ($result === false) {
        throw new Exception("Failed to submit poll answer");
    }

    return [
        "data" => null,
        "message" => "Poll answer submitted successfully",
        "status" => "success"
    ];
};
<?php

use classes\Poll;
use classes\Users;

return function ($data){
    $Poll = new Poll();
    $Users = new Users();

    $userData = $Users->GetUserFromSession($data["Session"]);

    if($userData === null) {
        throw new Exception("Invalid or expired session");
    }

    $hasAnswered = $Poll->HasUserAnswered($userData["idUsers"], $data["POLL_ID"]);

    return [
        "data" => [
            "has_answered" => $hasAnswered
        ],
        "message" => "Poll answer status retrieved successfully",
        "status" => "success"
    ];
};
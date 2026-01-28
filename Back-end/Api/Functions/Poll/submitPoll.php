<?php

use classes\Poll;
use classes\Users;

return function ($data) {
    $Users = new Users();
    $Poll = new Poll();

    $userData = $Users->GetUserFromSession($data["Session"]);

    if($userData === null) {
        throw new Exception("Invalid or expired session");
    }

    $PollId = $Poll->CreatePoll($data["QUESTION"], $userData["idUsers"], $data["EXPIRES"], $data["ANSWERS"]);


    return [
        "status" => "success",
        "message" => "Poll created successfully",
        "data" =>
            [
                "poll_id" => $PollId
            ]
    ];
};
<?php

use classes\Poll;

return function ($data) {
   $poll = new Poll();

    $pollData = $poll->GetPoll($data["POLL_ID"]);

    return [
        "data" => $pollData,
        "message" => "Poll data retrieved successfully",
        "status" => "success"
    ];
};
<?php

use classes\Poll;

return function ($data) {
    $poll = new Poll();

    $poll_data = $poll->GetPoll($data["POLL_ID"]);
    $options = $poll_data["answers"];
    foreach ($options as $option) {
        $pollData[] = [$option["Answer"] => $poll->GetPollAnswerCount($option["idPoll_answers"])];
    }

    return [
        "data" => $pollData,
        "message" => "Poll answers retrieved successfully",
        "status" => "success"
    ];
};
<?php

use classes\Poll;

return function ($data) {
    $poll = new Poll();

    $poll_data = $poll->GetPoll($data["POLL_ID"]);
    $options = $poll_data["answers"];
    foreach ($options as $option) {
        $pollData[] = [$poll->GetPollAnswerCount($option["idPoll_answers"]), $option["Answer"], $option["BarColour"]];
    }

    return [
        "data" => $pollData,
        "message" => "Poll answers retrieved successfully",
        "status" => "success"
    ];
};
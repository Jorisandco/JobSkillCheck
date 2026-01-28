<?php

use classes\Users;
use classes\Sessions;

return function ($data) {
    $Users = new Users();
    $sessions = new Sessions();

    $sessions->DeleteExpiredSession();

    $user = $Users->GetUserFromSession($data["Session"]);

    if($user === null) {
        throw new Exception("Invalid or expired session");
    }

    return [
        "data" => [
            "USER_ID" => $user["idUsers"],
            "EMAIL" => $user["Email"],
        ],
        "message" => "successful login"
    ];
};
<?php

use classes\Users;
use classes\Sessions;

return function ($data) {
    $Users = new Users();
    $sessions = new Sessions();

    $sessions->DeleteExpiredSession();

    $user = $Users->GetUserFromSession($data["Session"]);

    if($user === null) {
        return [
            "data" => [
               "isLoggedIn" => false
            ],
            "message" => "not logged in"
        ];
    }

    return [
        "data" => [
            "USER_ID" => $user["idUsers"],
            "EMAIL" => $user["Email"],
            "isLoggedIn" => true
        ],
        "message" => "successful login"
    ];
};
<?php

use classes\Users;

return function ($data) {
    $Users = new Users();

    $user = $Users->GetUserFromSession($data["Session"]);

    return [
        "data" => [
            "USER_ID" => $user["idUsers"],
            "EMAIL" => $user["Email"],
        ],
        "message" => "successful login"
    ];
};
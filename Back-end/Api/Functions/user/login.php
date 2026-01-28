<?php

use classes\Users;
use classes\sessions;

return function ($data): array {
    $Users = new Users();
    $sessions = new Sessions();

    $sessions->DeleteExpiredSession();

    if (!isset($data["EMAIL"]) && !filter_var($data["EMAIL"], FILTER_VALIDATE_EMAIL))
        throw new Exception("Invalid Email Format");

    $existingUsers = $Users->GetUsers($data["EMAIL"]);
    $loggedInTime = $data["stayLoggedIn"] ? date('Y-m-d H:i:s', strtotime('+30 years')) : date('Y-m-d H:i:s', strtotime('+7 days'));

    if (count($existingUsers) === 0) {
        $newUserId = $Users->CreateUser($data["EMAIL"]);
        $User_session = $sessions->CreateSession($newUserId, $loggedInTime);

        if ($newUserId === null) {
            throw new Exception("Failed to create user");
        }

        return [
            "data" => [
                "USER_ID" => $newUserId,
                "EMAIL" => $data["EMAIL"],
                "SESSION_ID" => $User_session,
            ],
            "message" => "user created successfully"
        ];
    }

    $existingUser = $existingUsers[0];

    $User_session = $sessions->CreateSession($existingUser["idUsers"], $loggedInTime);


    return [
        "data" => [
            "USER_ID" => $existingUser["idUsers"],
            "EMAIL" => $existingUser["Email"],
            "SESSION_ID" => $User_session,
        ],
        "message" => "successful login"
    ];
};
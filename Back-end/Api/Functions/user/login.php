<?php

return function ($data, $database): array {
    if (!isset($data["EMAIL"]) && !filter_var($data["EMAIL"], FILTER_VALIDATE_EMAIL))
        throw new Exception("Invalid Email Format");

    $existingUsers = $database->Users->GetUsers($data["EMAIL"]);

    if (count($existingUsers) === 0) {
        $newUserId = $database->Users->CreateUser($data["EMAIL"]);
        if ($newUserId === null) {
            throw new Exception("Failed to create user");
        }

        return [
            "data" => [
                "USER_ID" => $newUserId,
                "EMAIL" => $data["EMAIL"]
            ],
            "message" => "user created successfully"
        ];
    }

    $existingUser = $existingUsers[0];

    return [
        "data" => [
            "USER_ID" => $existingUser["id"],
            "EMAIL" => $existingUser["email"],
        ],
        "message" => "successful login"
    ];
};
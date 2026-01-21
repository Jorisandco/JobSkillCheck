<?php
require_once './headers/Headers.php';

use classes\DataBase;
use classes\Poll;
use classes\Users;

// END POINT LIST DEFINITION

$functionFolder = "../functions/";
$endpoints = [
    "API" =>
        [
            "User" =>
                [
                    "Login" => "$functionFolder/User/login.php",
                    "answer" => "$functionFolder/User/answer.php"
                ],
            "Poll" =>
                [
                    "getPollData" => "$functionFolder/Poll/getPollData.php",
                    "submitPoll" => "$functionFolder/Poll/submitPoll.php"
                ]
        ]
];

// REQUEST HANDLING
$userInput = json_decode(file_get_contents('php://input'), true);

$RequestLink = $_SERVER['REQUEST_URI'];

if (isset($userInput['endpoint'])) {
    $endpointParts = explode('/', $userInput['endpoint']);

    if (count($endpointParts) === 3) {
        $category = $endpointParts[0];
        $subCategory = $endpointParts[1];
        $action = $endpointParts[2];

        if (isset($endpoints[$category][$subCategory][$action])) {
            require_once $endpoints[$category][$subCategory][$action];
            exit();
        }
    }
}
<?php
require_once './headers/Headers.php';

use classes\BaseApiRetuns;

// END POINT LIST DEFINITION

$functionFolder = "../functions/";
$endpoints = [
    "API" =>
        [
            "empty_function" =>
                [
                    "test" => "$functionFolder/empty_function.php"
                ],
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
            try{
                $result = require_once $endpoints[$category][$subCategory][$action];
                BaseApiRetuns::ReturnSuccess($result, "Request Successful");
            }catch (\Throwable $e){
                BaseApiRetuns::ReturnError(null, "Endpoint Doesn't exist");
            }
        }
    }
}
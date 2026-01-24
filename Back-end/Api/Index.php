<?php

include "classes/BaseApiReturns.php";
include "classes/DataBase.php";

use classes\DataBase;
use classes\BaseApiReturns;

require_once './headers/Headers.php';

// END POINT LIST DEFINITION

$database = new DataBase();
$functionFolder = "functions";
$endpoints = [
    "API" =>
        [
            "empty_function" =>
                [
                    "test" => "$functionFolder/empty_function.php"
                ],
            "User" =>
                [
                    "Login" => "$functionFolder/user/login.php",
                    "answer" => "$functionFolder/user/answer.php"
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

if (!isset($userInput)) {
    BaseApiReturns::ReturnError(null, "No endpoint provided");
    exit();
}

$endpointParts = explode('/', $RequestLink);

if (count($endpointParts) !== 4) {
    BaseApiReturns::ReturnError(null, "Not a valid endpoint provided");
    exit();
}

$category = $endpointParts[1];
$subCategory = $endpointParts[2];
$action = $endpointParts[3];

echo !isset($endpoints[$category][$subCategory][$action]) . "\n";

if (!isset($endpoints[$category][$subCategory][$action])) {
    BaseApiReturns::ReturnError(null, "Endpoint Doesn't exist");
    exit();
}

try {
    $handler = require_once $endpoints[$category][$subCategory][$action];
    $result = $handler($userInput, $database);
    BaseApiReturns::ReturnSuccess($result["data"], $result["message"]);
} catch (\Throwable $e) {
    BaseApiReturns::ReturnError(null, "$e");
}
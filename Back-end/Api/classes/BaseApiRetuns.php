<?php

namespace classes;

use JetBrains\PhpStorm\NoReturn;

class BaseApiRetuns
{
    #[NoReturn] public static function ReturnError($data = null, $message = "an error occurred") :void
    {
        self::LogError(json_encode([
            "status" => "error",
            "message" => "$message",
            "data" => $data
        ]));

        exit();
    }

    #[NoReturn] public static function ReturnSuccess($data, $message) :void
    {
        echo json_encode([
            "status" => "success",
            "message" => "$message",
            "data" => $data
        ]);

        exit();
    }

    #[NoReturn] private static function LogError($message = "Invalid Endpoint"): void
    {
        echo $message;
    }
}
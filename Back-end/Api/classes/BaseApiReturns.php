<?php

namespace classes;

class BaseApiReturns
{
    public static function ReturnError($data = null, $message = "an error occurred"): void
    {
        self::LogError(json_encode([
            "status" => "error",
            "message" => "$message",
            "data" => $data
        ]));
    }

    public static function ReturnSuccess($data, $message): void
    {
        echo json_encode([
            "status" => "success",
            "message" => "$message",
            "data" => $data
        ]);
    }

    private static function LogError($message = "Invalid Endpoint"): void
    {
        echo $message;
    }
}
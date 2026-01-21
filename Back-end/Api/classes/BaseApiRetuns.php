<?php

namespace classes;

class BaseApiRetuns
{
    public static function ReturnError()
    {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid Endpoint"
        ]);

        self::LogError();

        exit();
    }

    public static function ReturnSuccess()
    {

    }

    private static function LogError()
    {

    }
}
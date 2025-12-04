<?php

namespace App\Helpers;

interface CustomError
{
    public function errorMessage();
}

class ResponseHelper
{
    public static function jsonResponse($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}
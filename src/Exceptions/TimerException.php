<?php

namespace App\Exceptions;

interface CustomError
{
    public function errorMessage();
}

class TimerException extends \Exception implements CustomError
{
    public function errorMessage()
    {
        return "Kesalahan: " . $this->getMessage();
    }
}

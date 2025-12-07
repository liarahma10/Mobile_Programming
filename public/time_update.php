<?php

require '../vendor/autoload.php';

use App\Helpers\ResponseHelper;
use App\Models\Stopwatch;
use App\Models\Timer;

$method = $_SERVER['REQUEST_METHOD'];

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true) ?? [];

$query = $_GET ?? [];

session_start();

if (!isset($_SESSION['stopwatch']) || !isset($_SESSION['timer'])) {
    ResponseHelper::jsonResponse(['error' => 'Stopwatch or Timer not initialized'], 400);
}

$stopwatch = $_SESSION['stopwatch'];
$timer = $_SESSION['timer'];

ResponseHelper::jsonResponse([
    'stopwatch' => $stopwatch->getElapsedTime(),
    'timer' => $timer->getRemainingTime(),
    'timerFinished' => $timer->isFinished()
]);

<?php

namespace App\Models;

class Stopwatch {
    private $startTime;
    private $endTime;
    private $running;

    public function __construct() {
        $this->startTime = null;
        $this->endTime = null;
        $this->running = false;
    }

    public function start() {
        if (!$this->running) {
            $this->startTime = microtime(true);
            $this->endTime = null;
            $this->running = true;
        }
    }

    public function stop() {
        if ($this->running) {
            $this->endTime = microtime(true);
            $this->running = false;
        }
    }

    public function reset() {
        $this->startTime = null;
        $this->endTime = null;
        $this->running = false;
    }

    public function getElapsedTime() {
        if ($this->running) {
            return round((microtime(true) - $this->startTime), 0);
        } elseif ($this->startTime && $this->endTime) {
            return round(($this->endTime - $this->startTime), 0);
        }
        return 0;
    }

    public function __sleep() {
        return ['startTime', 'endTime', 'running'];
    }

    public function __wakeup() {
        // Inisialisasi jika diperlukan
    }
}
<?php 

namespace App\Models;

class Timer {
    private $duration;
    private $startTime;
    private $remainingTime;
    private $running;

    public function __construct($duration) {
        if ($duration < 0) {
            throw new \InvalidArgumentException("Nilai harus lebih dari atau sama dengan 1.");
        }
        $this->duration = $duration;
        $this->remainingTime = $duration;
        $this->running = false;
    }

    public function start() {
        // Jika durasi 0, timer tidak perlu dimulai
        if ($this->duration == 0) {
            return;
        }
        if (!$this->running) {
            $this->startTime = microtime(true);
            $this->running = true;
        }
    }

    public function stop() {
        if ($this->running) {
            $elapsed = microtime(true) - $this->startTime;
            $this->remainingTime -= $elapsed;
            $this->running = false;
        }
    }

    public function reset() {
        $this->remainingTime = $this->duration;
        $this->running = false;
    }

    public function getRemainingTime() {
        // Durasi 0 berarti waktu yang tersisa selalu 0
        if ($this->duration == 0) {
            return 0;
        }
        if ($this->running) {
            $elapsed = microtime(true) - $this->startTime;
            return max(0, round(($this->remainingTime - $elapsed), 0));
        }
        return max(0, round($this->remainingTime, 0));
    }

    public function isFinished() {
        // Timer dengan durasi 0 dianggap selesai sejak awal
        if ($this->duration == 0) {
            return true;
        }
        return $this->getRemainingTime() <= 0;
    }

    public function __sleep() {
        return ['duration', 'startTime', 'remainingTime', 'running'];
    }

    public function __wakeup() {
        // Reinitialisasi jika diperlukan
    }
}

<?php

require '../vendor/autoload.php';

use App\Models\Timer;
use App\Models\Stopwatch;
use App\Exceptions\TimerException;

session_start();

if (!isset($_SESSION['stopwatch'])) {
    $_SESSION['stopwatch'] = new Stopwatch();
}

if (!isset($_SESSION['timer'])) {
    $_SESSION['timer'] = new Timer(60);
}

$stopwatch = $_SESSION['stopwatch'];
$timer = $_SESSION['timer'];

//menampung pesan kesalahan yang akan ditampilkan di halaman jika terjadi error
$errorMessage = null; 

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['start_stopwatch'])) {
            $stopwatch->start();
        } elseif (isset($_POST['stop_stopwatch'])) {
            $stopwatch->stop();
        } elseif (isset($_POST['reset_stopwatch'])) {
            $stopwatch->reset();
        } elseif (isset($_POST['start_timer'])) {
            $timer->start();
        } elseif (isset($_POST['stop_timer'])) {
            $timer->stop();
        } elseif (isset($_POST['reset_timer'])) {
            $timer->reset();
        } elseif (isset($_POST['set_timer'])) {
            $duration = intval($_POST['duration']);
            if ($duration <= 0) {
                throw new TimerException("Nilai harus lebih dari atau sama dengan 1.");
            }
            $_SESSION['timer'] = new Timer($duration);
            $timer = $_SESSION['timer'];
        }
    }
} catch (TimerException $e) {
    $errorMessage = $e->errorMessage();
} catch (\Exception $e) {
    $errorMessage = "Terjadi kesalahan: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stopwatch dan Timer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-5">
    <?php if ($errorMessage): ?>
    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <div class="card mb-4 shadow" style="background-color: #f0f8ff;">
      <div class="card-body">
        <h1 class="text-center text-primary">Stopwatch</h1>
        <p class="text-center">Waktu Berlalu: <span id="stopwatch-time"
            class="fw-bold text-dark"><?php echo $stopwatch->getElapsedTime(); ?></span> detik</p>
        <form method="post" class="d-flex gap-2 justify-content-center">
          <button type="submit" name="start_stopwatch" class="btn btn-primary">Mulai</button>
          <button type="submit" name="stop_stopwatch" class="btn btn-warning">Berhenti</button>
          <button type="submit" name="reset_stopwatch" class="btn btn-danger">Reset</button>
        </form>
      </div>
    </div>

    <div class="card shadow" style="background-color: #e6ffe6;">
      <div class="card-body">
        <h1 class="text-center text-success">Timer</h1>
        <p class="text-center">Waktu Tersisa: <span id="timer-time"
            class="fw-bold text-dark"><?php echo $timer->getRemainingTime(); ?></span> detik</p>
        <p id="timer-finished" class="text-danger fw-bold d-none text-center">Timer Selesai!</p>
        <form method="post" class="d-flex flex-column gap-3 align-items-center">
          <div class="d-flex gap-2">
            <button type="submit" name="start_timer" class="btn btn-primary">Mulai</button>
            <button type="submit" name="stop_timer" class="btn btn-warning">Berhenti</button>
            <button type="submit" name="reset_timer" class="btn btn-danger">Reset</button>
          </div>
          <div class="d-flex align-items-center gap-2">
            <label for="duration" class="text-muted">Set Timer (detik): </label>
            <input type="number" name="duration" id="duration" class="form-control w-25" min="1">
            <button type="submit" name="set_timer" class="btn btn-success">Set Timer</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
  // Fungsi untuk memperbarui waktu stopwatch dan timer setiap detik
  function updateTime() {
    fetch('time_update.php')
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          console.error(data.error);
          return;
        }

        // Update stopwatch time
        document.getElementById('stopwatch-time').textContent = data.stopwatch;

        // Update timer time
        document.getElementById('timer-time').textContent = data.timer;

        // Tampilkan pesan jika timer selesai
        const timerFinishedMessage = document.getElementById('timer-finished');
        if (data.timerFinished) {
          timerFinishedMessage.classList.remove('d-none');
        } else {
          timerFinishedMessage.classList.add('d-none');
        }
      })
      .catch(error => console.error('Error fetching time data:', error));
  }

  // Memanggil updateTime setiap detik
  setInterval(updateTime, 1000);
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
session_start();

function is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start_timer'])) {
        // 타이머 시작
        if (!isset($_SESSION['start_time'])) {
            $_SESSION['start_time'] = time(); // 새로운 시작 시간
        }
        if (is_ajax_request()) {
            echo json_encode(["status" => "started", "start_time" => $_SESSION['start_time']]);
            exit;
        }
    }
    if (isset($_POST['stop_timer'])) {
        // 타이머 멈추기
        $elapsed_time = isset($_SESSION['start_time']) ? time() - $_SESSION['start_time'] : 0;
        $_SESSION['elapsed_time'] = isset($_SESSION['elapsed_time']) ? $_SESSION['elapsed_time'] + $elapsed_time : $elapsed_time; // 멈췄을 때의 시간 저장
        unset($_SESSION['start_time']); // 시작 시간 초기화
        if (is_ajax_request()) {
            echo json_encode(["status" => "stopped", "elapsed_time" => $_SESSION['elapsed_time']]);
            exit;
        }
    }
    if (isset($_POST['reset_timer'])) {
        // 타이머 초기화
        unset($_SESSION['start_time']);
        unset($_SESSION['elapsed_time']);
        if (is_ajax_request()) {
            echo json_encode(["status" => "reset"]);
            exit;
        }
    }
}

// 시작 시간 또는 멈춘 후의 시간을 기준으로 현재 시간을 계산
$elapsed_time = isset($_SESSION['elapsed_time']) ? $_SESSION['elapsed_time'] : (isset($_SESSION['start_time']) ? time() - $_SESSION['start_time'] : 0);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>타이머</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'DS-Digital';
            src: url('fonts/DS-DIGI.TTF') format('truetype');
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        h1 {
            font-family: 'Roboto', sans-serif;
            font-size: 36px;
            margin-bottom: 25px;
            margin-top: 5px;
        }

        #timer {
            font-family: 'DS-Digital';
            background-color: #333;
            font-size: 4.5em;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 250px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        #wrap {
            border-radius: 20px;
            text-align: center;
            padding: 20px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.4);
        }

        button {
            font-family: 'Roboto', sans-serif;
            background-color: #979797;
            color: white;
            padding: 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 80px;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:disabled {
            background-color: #d3d3d3;
            cursor: not-allowed;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
    </style>
    <script>
        let elapsedTime = <?php echo $elapsed_time; ?>;
        let interval;

        function updateTimerDisplay() {
            const hours = Math.floor(elapsedTime / 3600);
            const minutes = Math.floor((elapsedTime % 3600) / 60);
            const seconds = elapsedTime % 60;
            document.getElementById('timer').textContent = `${hours}:${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        }

        function startTimer() {
            if (!interval) {
                fetch("", {
                    method: "POST",
                    headers: { "X-Requested-With": "XMLHttpRequest" },
                    body: new URLSearchParams({ start_timer: "1" })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "started") {
                            interval = setInterval(() => {
                                elapsedTime++;
                                updateTimerDisplay();
                            }, 1000);
                        }
                    });
            }
        }

        function stopTimer() {
            fetch("", {
                method: "POST",
                headers: { "X-Requested-With": "XMLHttpRequest" },
                body: new URLSearchParams({ stop_timer: "1" })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "stopped") {
                        clearInterval(interval);
                        interval = null;
                        elapsedTime = data.elapsed_time;
                        updateTimerDisplay();
                    }
                });
        }

        function resetTimer() {
            fetch("", {
                method: "POST",
                headers: { "X-Requested-With": "XMLHttpRequest" },
                body: new URLSearchParams({ reset_timer: "1" })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "reset") {
                        clearInterval(interval);
                        interval = null;
                        elapsedTime = 0;
                        updateTimerDisplay();
                    }
                });
        }

        document.addEventListener("DOMContentLoaded", function() {
            updateTimerDisplay();
            document.getElementById("startButton").addEventListener("click", function(event) {
                event.preventDefault();
                startTimer();
            });
            document.getElementById("stopButton").addEventListener("click", function(event) {
                event.preventDefault();
                stopTimer();
            });
            document.getElementById("resetButton").addEventListener("click", function(event) {
                event.preventDefault();
                resetTimer();
            });
        });
    </script>
</head>
<body>
    <div id="wrap">
        <h1>Study Timer</h1>
        <div id="timer"></div>
        <div class="button-container">
            <button id="startButton">Start</button>
            <button id="stopButton">Stop</button>
            <button id="resetButton">Reset</button>
        </div>
    </div>
</body>
</html>

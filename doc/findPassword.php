<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>ZIP 비밀번호 찾기</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f0f4f8;
            font-family: 'Apple SD Gothic Neo', 'Noto Sans KR', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
        }

        input[type="file"] {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 6px;
            width: 100%;
            background-color: #fdfdfd;
        }

        button {
            background-color: #00a86b;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #007a52;
        }

        #result {
            margin-top: 20px;
            font-size: 18px;
            font-weight: 500;
        }

        .success {
            color: #2ecc71;
        }

        .error {
            color: #e74c3c;
        }

        .loading {
            color: #2980b9;
        }
    </style>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>🔐 ZIP 비밀번호 찾기</h2>
    <form id="uploadForm" enctype="multipart/form-data">
        <input type="file" name="zipfile" accept=".zip" required>
        <button type="submit">비밀번호 찾기</button>
    </form>
    <div id="result"></div>
    <a href="index.php">
        <button class="back-button" type="button">메인으로 돌아가기</button>
    </a>
</div>

<script>
    const form = document.getElementById('uploadForm');
    const resultDiv = document.getElementById('result');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(form);
        resultDiv.innerHTML = '<span class="loading">🔍 비밀번호를 찾는 중입니다...</span>';

        fetch('unzip.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.text())
            .then(data => {
                resultDiv.innerHTML = data;
            })
            .catch(err => {
                resultDiv.innerHTML = '<span class="error">❌ 오류가 발생했습니다.</span>';
            });
    });
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>파일 변환 서비스</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #8EC5FC, #E0C3FC);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 450px;
            backdrop-filter: blur(10px);
        }

        h1 {
            color: #333;
            margin-bottom: 15px;
            font-size: 26px;
            font-weight: 600;
        }

        span {
            font-size: 14px;
            color: #666;
            display: block;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        a {
            text-decoration: none;
        }

        button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: #3498db;
            color: white;
            font-size: 18px;
            font-weight: 500;
            padding: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        button:active {
            background: #1f6690;
            transform: scale(0.98);
        }

        .button-container a:nth-child(2) button {
            background: #2ecc71;
        }

        .button-container a:nth-child(2) button:hover {
            background: #27ae60;
        }

        .button-container a:nth-child(3) button {
            background: #e74c3c;
        }

        .button-container a:nth-child(3) button:hover {
            background: #c0392b;
        }

        .icon {
            font-size: 20px;
        }

        /* 제작자 표시 스타일 */
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #444;
            background: rgba(255, 255, 255, 0.8);
            padding: 8px 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <h1>📂 파일 변환 서비스</h1>
    <span>한 번에 변환 가능한 파일 개수: <strong>150개</strong></span>
    <div class="button-container">
        <a href="jpgToPdf.php">
            <button><i class="fas fa-file-image icon"></i> JPG to PDF 변환</button>
        </a>
        <a href="pngToPdf.php">
            <button><i class="fas fa-file-image icon"></i> PNG to PDF 변환</button>
        </a>
        <a href="heicToJpg.php">
            <button><i class="fas fa-images icon"></i> HEIC to JPG 변환</button>
        </a>
    </div>
    <div class="footer">👩🏻‍💻 Developed by PEB</div>
</div>
</body>
</html>

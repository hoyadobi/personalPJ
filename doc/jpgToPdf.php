<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JPG to PDF 변환</title>
    <link href="css/style.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fb;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }

        #uploadForm {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="file"], input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        button {
            background-color: #00b300;
            color: white;
            font-size: 18px;
            font-weight: 600;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #007f00;
        }

        #progress {
            font-size: 20px;
            font-weight: 600;
            margin-top: 20px;
            color: #00b300;
        }

        .error {
            color: #e74c3c;
            font-size: 18px;
            font-weight: 600;
        }

        .success {
            color: #2ecc71;
            font-size: 18px;
            font-weight: 600;
        }
        .back-button {
            background-color: #4CAF50; /* 버튼 배경색 */
            color: white; /* 버튼 글자색 */
            padding: 8px 15px; /* 버튼 내부 여백 (상하, 좌우) */
            font-size: 14px; /* 글자 크기 */
            border: none; /* 테두리 제거 */
            border-radius: 5px; /* 모서리 둥글게 */
            cursor: pointer; /* 마우스 커서 모양 */
            transition: background-color 0.3s; /* 배경색 전환 효과 */
        }

        .back-button:hover {
            background-color: #45a049; /* 버튼에 마우스를 올렸을 때 배경색 변화 */
        }
    </style>
</head>
<body>
<div class="container">
    <h2>JPG 파일을 PDF로 변환</h2>
    <form id="uploadForm" enctype="multipart/form-data">
        <input type="file" name="images[]" accept="image/jpeg" multiple required>
        <input type="text" id="pdfName" name="pdfName" placeholder="저장할 PDF 이름" required>
        <button id="convertButton" type="button">변환하기</button>
    </form>

    <div id="progress">0%</div> <!-- 진행 상태 표시 -->
    <a href="index.php">
        <button class="back-button" type="button">메인으로 돌아가기</button>
    </a>
</div>

<script>
    function uploadAndConvert() {
        let form = document.getElementById("uploadForm");
        let pdfName = document.getElementById("pdfName").value.trim();

        if (!form) {
            console.error("❌ uploadForm을 찾을 수 없습니다.");
            alert("폼이 존재하지 않습니다. 페이지를 새로고침 해보세요.");
            return;
        }

        if (!pdfName) {
            alert("PDF 이름을 입력해 주세요.");
            return;
        }

        // 파일 이름을 URL 인코딩
        pdfName = encodeURIComponent(pdfName);

        let formData = new FormData(form);
        formData.append("pdfName", pdfName); // 파일 이름 추가

        let xhr = new XMLHttpRequest();
        console.log("📤 변환 요청 시작");
        xhr.open("POST", "convert.php", true);

        // 파일 업로드 진행 상태 추적
        xhr.upload.onprogress = function(event) {
            if (event.lengthComputable) {
                let percent = Math.round((event.loaded / event.total) * 100);
                updateProgress(percent);
            }
        };

        xhr.onload = function() {
            console.log("📥 서버 응답 도착:", xhr.status, xhr.responseText);

            if (xhr.status == 200) {
                let response;
                try {
                    response = JSON.parse(xhr.responseText);
                    console.log("✅ JSON 응답:", response);
                } catch (e) {
                    console.error("❌ JSON 파싱 오류:", e, xhr.responseText);
                    alert("서버 응답이 올바르지 않습니다.");
                    return;
                }

                if (response.success) {
                    console.log("📂 변환된 파일 다운로드:", response.file);
                    window.location.href = response.file;
                } else {
                    console.error("❌ 변환 실패:", response.error);
                    alert("변환 실패: " + response.error);
                }
            } else {
                console.error("❌ 서버 오류 발생:", xhr.status);
                alert("서버 오류 발생: " + xhr.status);
            }
        };

        xhr.onerror = function() {
            console.error("❌ 네트워크 오류 발생");
            alert("네트워크 오류 발생! 서버가 실행 중인지 확인하세요.");
        };

        xhr.send(formData);
    }

    function updateProgress(progress) {
        const progressElement = document.getElementById("progress");
        if (progressElement) {
            progressElement.innerText = progress + '%';
        }
    }

    // 파일 업로드 크기 제한 체크
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("uploadForm").onsubmit = function() {
            const files = document.querySelector('input[type="file"]').files;
            let totalSize = 0;
            for (let i = 0; i < files.length; i++) {
                totalSize += files[i].size;
            }

            if (totalSize > 200 * 1024 * 1024) {  // 200MB
                alert("업로드 파일 크기가 너무 큽니다.");
                return false;
            }
        };

        // 변환 요청 버튼 클릭 시 처리
        document.getElementById("convertButton").addEventListener("click", uploadAndConvert);
    });
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEIC to JPG 변환</title>
    <link href="css/style_h.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>HEIC to JPG 변환 및 ZIP 다운로드</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="heicFiles[]" accept=".heic" multiple required>
        <button type="submit">변환 및 다운로드</button>
    </form>
    <!-- 메인 페이지로 돌아가는 버튼 -->
    <a href="index.php">
        <button class="back-button" type="button">메인으로 돌아가기</button>
    </a>
</div>
</body>
</html>
<?php
// HEIC → JPG 변환 함수
function convertHeicToJpg($heicFile, $jpgFile) {
    $imagick = new Imagick();

    try {
        $imagick->readImage($heicFile);
        $imagick->setImageFormat('jpeg');

        // 변환된 파일 저장
        if (!$imagick->writeImage($jpgFile)) {
            throw new Exception("파일 저장 실패: " . $jpgFile);
        }

        $imagick->clear();
        $imagick->destroy();

        // 변환된 파일 존재 여부 확인
        if (!file_exists($jpgFile)) {
            throw new Exception("변환 후 파일이 존재하지 않음: " . $jpgFile);
        }
    } catch (Exception $e) {
        die("이미지 변환 오류: " . $e->getMessage());
    }
}

// 파일 업로드 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['heicFiles'])) {
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $jpgFiles = [];
    $zipFile = $uploadDir . 'converted_images.zip';

    $zip = new ZipArchive();
    if ($zip->open($zipFile, ZipArchive::CREATE) !== TRUE) {
        die("ZIP 파일을 생성할 수 없습니다.");
    }

    foreach ($_FILES['heicFiles']['tmp_name'] as $index => $tmpName) {
        if (!file_exists($tmpName)) {
            die("업로드된 파일이 존재하지 않음: " . $_FILES['heicFiles']['name'][$index]);
        }

        $heicFile = $_FILES['heicFiles']['name'][$index];
        $jpgFile = $uploadDir . pathinfo($heicFile, PATHINFO_FILENAME) . '.jpeg';

        convertHeicToJpg($tmpName, $jpgFile);

        if (!file_exists($jpgFile)) {
            die("JPG 변환 실패: " . $jpgFile . " 이 존재하지 않습니다.");
        }

        if (!$zip->addFile($jpgFile, basename($jpgFile))) {
            die("ZIP 추가 실패: " . $jpgFile);
        }

        $jpgFiles[] = $jpgFile;
    }

    $zip->close();

    // 변환된 JPG 파일 삭제
    foreach ($jpgFiles as $jpgFile) {
        unlink($jpgFile);
    }

    // ZIP 파일 다운로드
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="converted_images.zip"');
    header('Content-Length: ' . filesize($zipFile));
    readfile($zipFile);

    // ZIP 파일 삭제
    unlink($zipFile);
    exit;
}
?>



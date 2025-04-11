<?php
// 세션 시작 및 JSON 헤더 설정
session_start();
header('Content-Type: application/json');

// 업로드 파일 처리
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_FILES['images'])) {
    // 클라이언트로부터 받은 PDF 이름
    $pdfName = isset($_POST['pdfName']) ? $_POST['pdfName'] : 'output'; // 기본값은 'output'

    // 업로드된 파일 정보 처리
    $totalFiles = count($_FILES['images']['tmp_name']);
    $pdf = new Imagick();
    $pdf->setResolution(300, 300);
    $_SESSION['progress'] = 0;

    // 이미지 추가
    foreach ($_FILES['images']['tmp_name'] as $imagePath) {
        if (file_exists($imagePath)) {
            $img = new Imagick($imagePath);
            $img->setImageFormat('pdf');
            $pdf->addImage($img);
            unset($img); // 메모리 해제
        }
    }

    // 업로드 오류 체크
    $error = $_FILES['images']['error'][0];
    if ($error !== UPLOAD_ERR_OK) {
        echo json_encode(["success" => false, "error" => '파일 업로드 오류 발생']);
        exit;
    }

    // 이미지가 없으면 오류 처리
    if ($pdf->getNumberImages() == 0) {
        echo json_encode(["success" => false, "error" => "PDF에 추가된 이미지가 없습니다."]);
        exit;
    }

    // 저장할 PDF 파일 경로 (파일 이름을 포함하여 저장)
    $uploadDir = __DIR__ . '/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // 디렉토리 없으면 생성
    }
    $outputFile = $uploadDir . '/' . $pdfName . '.pdf';

    // PDF 저장
    try {
        $pdf->writeImages($outputFile, true);
        echo json_encode(["success" => true, "file" => $outputFile]);
    } catch (ImagickException $e) {
        echo json_encode(["success" => false, "error" => "PDF 저장 중 오류 발생: " . $e->getMessage()]);
        exit;
    }
}

?>

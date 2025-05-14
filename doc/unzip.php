<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['zipfile'])) {
    $file = $_FILES['zipfile'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $zipPath = $file['tmp_name'];
        $zip = new ZipArchive();

        for ($i = 0; $i <= 9999; $i++) {
            $password = "Wlszhd" . str_pad($i, 4, "0", STR_PAD_LEFT);

            if ($zip->open($zipPath) === TRUE) {
                if ($zip->setPassword($password)) {
                    $entryName = $zip->getNameIndex(0);
                    $stream = @$zip->getStream($entryName);

                    if ($stream) {
                        $data = @fread($stream, 10);
                        fclose($stream);

                        if ($data !== false && strlen($data) > 0) {
                            $zip->close();
                            echo "<p class='success'>✅ 비밀번호는 <strong>$password</strong> 입니다.</p>";
                            exit;
                        }
                    }
                }
                $zip->close();
            }
        }

        echo "<p class='error'>❌ 비밀번호를 찾을 수 없습니다.</p>";
    } else {
        echo "<p class='error'>❌ 파일 업로드 실패</p>";
    }
} else {
    echo "<p class='error'>❌ 잘못된 요청입니다.</p>";
}
?>

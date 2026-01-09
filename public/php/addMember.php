<?php

// --- 測試用：開啟錯誤顯示 (修好後記得刪除) ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ----------------------------------------
require 'conn.php';
require 'sendGmail.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('無效的請求方法');
    }

    //接收前端 FormData 傳來的欄位
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $status = $_POST['status'] ?? 1;

    // 基本驗證
    if (empty($name) || empty($email) || empty($password)) {
        throw new Exception('請填寫完整資訊 (姓名、Email、密碼)');
    }

    //處理圖片上傳
    $avatarPath = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../member/'; // 圖片存放資料夾
        
        // 如果資料夾不存在，嘗試建立
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // 取得副檔名並重新命名以防重複
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $newFileName = time() . '_' . uniqid() . '.' . $ext;
        $targetFile = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
            $avatarPath = $targetFile; // 存入資料庫的路徑
        }
    }

    // 寫入資料庫
    // SQL 欄位請依照你實際的資料庫調整，這裡假設有 avatar 欄位
    $sql = "INSERT INTO member (email, `name`, `password`, `status`, headshot, createdate, updatetime, `role`) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW(), 0)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $name, $password, $status, $avatarPath]);

    $newId = $pdo->lastInsertId();

    // 5. 寄送開通通知信
    $mailSent = false;
    // 呼叫 sendGmail.php 裡的函式
    if (sendPasswordEmail($email, $name, $password)) {
        $mailSent = true;
    }

    // 6. 回傳成功訊息給前端
    echo json_encode([
        'success' => true,
        'message' => '會員新增成功' . ($mailSent ? '，且已發送通知信' : '，但通知信發送失敗'),
        'id' => $newId,
        'mailSent' => $mailSent
    ]);

} catch (Exception $e) {
    // 錯誤處理
    http_response_code(400); // 或 500
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (PDOException $e) {
    // 資料庫錯誤處理
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '資料庫錯誤：' . $e->getMessage()
    ]);
}
?>
<?php
require 'conn.php';

try {
    if (empty($_POST['title_zh']) || empty($_POST['launchdate'])) {
        throw new Exception('標題與日期為必填欄位');
    }

    $sql = "INSERT INTO annalevent (
        title_zh, 
        title_en,
        content_zh, 
        content_en,
        content_summary_zh,
        content_summary_en,
        video, 
        createdate,
        launchdate,
        `status`, 
        pic
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ''
    )";

    // 執行 SQL
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['title_zh'],
        $_POST['title_en'] ?? '', 
        $_POST['content_zh'] ?? '',
        $_POST['content_en'] ?? '',
        $_POST['content_summary_zh'] ?? '',
        $_POST['content_summary_en'] ?? '',
        $_POST['video'] ?? '',
        $_POST['launchdate'],
        $_POST['status'] ?? 0 ,
    ]);

    // 3. 取得剛產生的 eventID (Auto Increment)
    $newEventID = $pdo->lastInsertId();

    // 4. 處理圖片上傳
    
    // ★ 設定圖片存放資料夾 (建議與 News 分開，或是依你的習慣調整)
    $uploadDir = '../festivals/'; 
    
    // 如果資料夾不存在就建立
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $dbImagePath = ''; // 準備寫入資料庫的路徑

    // 檢查是否有上傳圖片 (前端欄位名稱為 pic)
    if (isset($_FILES['pic']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
        
        $ext = pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION);
        
        // 檔名命名規則：event_ID_隨機碼.副檔名
        $fileName = 'event_' . $newEventID . '_' . uniqid() . '.' . $ext;
        
        // 移動檔案
        if (move_uploaded_file($_FILES['pic']['tmp_name'], $uploadDir . $fileName)) {
            // 設定要存入資料庫的路徑 (相對路徑)
            $dbImagePath = 'festivals/' . $fileName;
        }
    }

    // 如果有上傳圖片，更新資料庫欄位
    if (!empty($dbImagePath)) {
        // ★ 注意：請確認資料庫的主鍵名稱是 eventID 還是 id
        $sqlUpdate = "UPDATE annalevent SET pic = ? WHERE annalevent_ID = ?";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([$dbImagePath, $newEventID]);
    }

    echo json_encode([
        'success' => true, 
        'message' => '新增成功',
        'id' => $newEventID
    ]);

} catch (Exception $e) {
    // 發生錯誤
    http_response_code(500); 
    echo json_encode([
        'success' => false, 
        'message' => '處理失敗: ' . $e->getMessage()
    ]);
}
?>
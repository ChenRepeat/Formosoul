<?php
require 'conn.php';

try {
    if (empty($_POST['annalevent_ID']) || empty($_POST['title_zh']) || empty($_POST['launchdate'])) {
        throw new Exception('缺少必要欄位 (ID, 標題或日期)');
    }

    $eventID = $_POST['annalevent_ID'];

    $sql = "UPDATE annalevent SET 
            title_zh = ?, 
            title_en = ?,
            content_zh = ?, 
            content_en = ?,
            content_summary_zh = ?, 
            content_summary_en = ?,
            video = ?,
            launchdate = ?, 
            `status` = ?
            WHERE annalevent_ID = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['title_zh'],
        $_POST['title_en'] ?? '', 
        $_POST['content_zh'] ?? '',
        $_POST['content_en'] ?? '',
        $_POST['content_summary_zh'] ?? '', // 這裡直接用前端傳來的簡介，不自動截取
        $_POST['content_summary_en'] ?? '',
        $_POST['video'] ?? '',
        $_POST['launchdate'],
        $_POST['status'] ?? 0,
        $eventID
    ]);

    // 3. 處理圖片更新 (只有當使用者有上傳新圖片時才執行)
    if (isset($_FILES['pic']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
        
        // 設定圖片存放資料夾 (對應 addAnnualEvent.php)
        $uploadDir = '../festivals/'; 
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION);
        // 檔名規則：event_ID_隨機碼
        $fileName = 'event_' . $eventID . '_' . uniqid() . '.' . $ext;
        
        // 移動檔案
        if (move_uploaded_file($_FILES['pic']['tmp_name'], $uploadDir . $fileName)) {
            
            // ==========================================
            // ★★★ 刪除舊圖片邏輯 ★★★
            // ==========================================
            
            // 1. 先去資料庫撈出「舊的圖片路徑」
            $sqlGetOld = "SELECT pic FROM annalevent WHERE annalevent_ID = ?";
            $stmtGetOld = $pdo->prepare($sqlGetOld);
            $stmtGetOld->execute([$eventID]);
            $oldPicPath = $stmtGetOld->fetchColumn(); // 例如 "festivals/old_abc.jpg"

            // 2. 檢查舊路徑是否存在，若有值且檔案存在就刪除
            if ($oldPicPath) {
                // 補上實體路徑前綴 "../"
                $oldFilePhysicalPath = '../' . $oldPicPath;
                
                if (file_exists($oldFilePhysicalPath)) {
                    unlink($oldFilePhysicalPath); // 執行刪除
                }
            }
            // ==========================================

            // 寫入資料庫的新路徑
            $dbImagePath = 'festivals/' . $fileName;

            // 更新圖片欄位
            $sqlUpdatePic = "UPDATE annalevent SET pic = ? WHERE annalevent_ID = ?";
            $stmtUpdatePic = $pdo->prepare($sqlUpdatePic);
            $stmtUpdatePic->execute([$dbImagePath, $eventID]);
        }
    }

    echo json_encode([
        'success' => true, 
        'message' => '更新成功',
        'id' => $eventID
    ]);

} catch (Exception $e) {
    // 發生錯誤
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => '更新失敗: ' . $e->getMessage()
    ]);
}
?>
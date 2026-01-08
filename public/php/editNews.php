<?php
require 'conn.php';

try {
    if (empty($_POST['newsID']) || empty($_POST['title_zh']) || empty($_POST['createdate'])) {
        throw new Exception('缺少必要欄位 (newsID, 標題或日期)');
    }

    $newsID = $_POST['newsID'];

    $sql = "UPDATE news SET 
            title_zh = ?, 
            title_en = ?,
            intro_zh = ?,     
            intro_en = ?, 
            content_zh = ?, 
            content_en = ?, 
            createdate = ?, 
            `status` = ?
            WHERE newsID = ?";

    // 準備簡介內容 (邏輯同新增：自動截取前 30 字)
    // 建議統一抓取 content_zh，若你前端欄位是 content，請自行調整
    $content_zh = $_POST['content_zh'] ?? '';
    $content_en = $_POST['content_en'] ?? '';

    $intro_zh = mb_substr($content_zh, 0, 30, 'utf-8'); 
    $intro_en = mb_substr($content_en, 0, 30, 'utf-8');

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['title_zh'],
        $_POST['title_en'] ?? '', 
        $intro_zh,
        $intro_en,
        $content_zh,
        $content_en,
        $_POST['createdate'],
        $_POST['status'] ?? 1,
        $newsID // WHERE 條件的 ID
    ]);

    // 3. 處理圖片更新 (只有當使用者有上傳新圖片時才執行)
    if (isset($_FILES['pic']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
        
        // 設定圖片存放資料夾
        $uploadDir = '../News/'; 
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION);
        $fileName = 'news_' . $newsID . '_' . uniqid() . '.' . $ext;
        
        // 移動檔案 (先確認新圖片上傳成功，再來刪舊圖，比較安全)
        if (move_uploaded_file($_FILES['pic']['tmp_name'], $uploadDir . $fileName)) {
            
            // ==========================================
            // ★★★ 新增：刪除舊圖片邏輯 ★★★
            // ==========================================
            
            // 1. 先去資料庫撈出「舊的圖片路徑」
            $sqlGetOld = "SELECT pic FROM news WHERE newsID = ?";
            $stmtGetOld = $pdo->prepare($sqlGetOld);
            $stmtGetOld->execute([$newsID]);
            $oldPicPath = $stmtGetOld->fetchColumn(); // 例如抓到 "News/old_abc.jpg"

            // 2. 檢查舊路徑是否存在，若有值且檔案存在就刪除
            if ($oldPicPath) {
                // 因為資料庫存的是 "News/xxx.jpg"，而 PHP 在下一層，所以要補上 "../"
                $oldFilePhysicalPath = '../' . $oldPicPath;
                
                if (file_exists($oldFilePhysicalPath)) {
                    unlink($oldFilePhysicalPath); // 執行刪除實體檔案
                }
            }
            // ==========================================
            // ★★★ 刪除結束 ★★★
            // ==========================================

            // 寫入資料庫的新路徑
            $dbImagePath = 'News/' . $fileName;

            // 更新圖片欄位
            $sqlUpdatePic = "UPDATE news SET pic = ? WHERE newsID = ?";
            $stmtUpdatePic = $pdo->prepare($sqlUpdatePic);
            $stmtUpdatePic->execute([$dbImagePath, $newsID]);
        }
    }

    echo json_encode([
        'success' => true, 
        'message' => '更新成功',
        'id' => $newsID
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
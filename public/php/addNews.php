<?php
require 'conn.php';

try {
    if (empty($_POST['title_zh']) || empty($_POST['createdate'])) {
        throw new Exception('標題與日期為必填欄位');
    }

    // 寫入 news 資料表
    $sql = "INSERT INTO news (
        title_zh, 
        title_en,
        intro_zh,    
        intro_en, 
        content_zh, 
        content_en, 
        createdate, 
        `status`, 
        pic
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ''
    )";

    // 準備簡介內容 (這裡示範：自動截取內容的前 30 個字當簡介)
    // 如果你想單純留白，就將下方變數改成 $intro_zh = '';
    $intro_zh = mb_substr($_POST['content'] ?? '', 0, 30, 'utf-8'); 
    $intro_en = mb_substr($_POST['content_en'] ?? '', 0, 30, 'utf-8');

    // 這裡先填入空字串 '' 作為圖片路徑，等取得 ID 並上傳圖片後再更新
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['title_zh'],
        $_POST['title_en'] ?? '', 
        $intro_zh,
        $intro_en,
        $_POST['content_zh'] ?? '',
        $_POST['content_en'] ?? '',
        $_POST['createdate'],
        $_POST['status'] ?? 1 ,
    ]);

    // ★ 關鍵差異：消息 ID 通常是 Auto Increment，所以用這個方法取得剛產生的 ID
    $newNewsID = $pdo->lastInsertId();

    // 處理圖片上傳
    // 設定圖片存放資料夾
    $uploadDir = '../News/'; 
    
    // 如果資料夾不存在就建立
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $dbImagePath = ''; // 準備寫入資料庫的路徑

    // 檢查是否有上傳圖片 (前端欄位名稱為 image)
    if (isset($_FILES['pic']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
        
        $ext = pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION);
        
        // 檔名命名規則：news_ID_隨機碼.副檔名 (避免檔名重複)
        $fileName = 'news_' . $newNewsID . '_' . uniqid() . '.' . $ext;
        
        // 移動檔案
        if (move_uploaded_file($_FILES['pic']['tmp_name'], $uploadDir . $fileName)) {
            // 設定要存入資料庫的路徑 (視你的前端如何讀取圖片，這裡存相對路徑)
            $dbImagePath = 'News/' . $fileName;
        }
    }

    // ===========================================================
    // 第三步：如果有上傳圖片，更新資料庫欄位
    // ===========================================================
    if (!empty($dbImagePath)) {
        $sqlUpdate = "UPDATE news SET pic = ? WHERE newsID = ?";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([$dbImagePath, $newNewsID]);
    }

    echo json_encode([
        'success' => true, 
        'message' => '新增成功',
        'id' => $newNewsID
    ]);

} catch (Exception $e) {
    // 發生錯誤，回滾交易
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // 回傳錯誤訊息
    http_response_code(500); // 設定 HTTP 狀態碼為 500 (伺服器錯誤)
    echo json_encode([
        'success' => false, 
        'message' => '處理失敗: ' . $e->getMessage()
    ]);
}
?>
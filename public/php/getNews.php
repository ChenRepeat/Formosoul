<?php

require 'conn.php';

try {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        
        // 取得單筆資料 (編輯頁用) 
        
        $id = $_GET['id'];
        
        $sql = "SELECT * FROM news WHERE newsID = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        
        // fetch(PDO::FETCH_ASSOC) 只抓取一筆，回傳的是物件
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // 回傳單筆 JSON 物件，例如: { "newsID": 1, "title": "...", ... }
        echo json_encode($row);

    } else {

        // --- 模式二：取得全部列表 (列表頁用) ---

        $sql = "SELECT * FROM news ORDER BY newsID DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        // fetchAll(PDO::FETCH_ASSOC) 抓取全部，回傳的是陣列
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
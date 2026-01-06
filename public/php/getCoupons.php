<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'conn.php';

try {
    // 檢查網址是否有帶 id 參數 (例如: getCoupons.php?id=5)
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        
        // --- 模式一：取得單筆資料 (編輯頁用) ---
        
        $id = $_GET['id'];
        
        // 依照 coupons_ID 撈取那一筆
        $sql = "SELECT * FROM coupons WHERE coupons_ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        
        // fetch(PDO::FETCH_ASSOC) 只抓取一筆，回傳的是物件 (關聯陣列)
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // 如果找不到資料，回傳 null 或空陣列，這裡直接回傳 false 讓前端判斷
        echo json_encode($row);

    } else {

        // --- 模式二：取得全部列表 (列表頁用) ---
        
        $sql = "SELECT * FROM coupons ORDER BY coupons_ID DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        // fetchAll(PDO::FETCH_ASSOC) 抓取全部，回傳的是陣列
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }

} catch (Exception $e) {
    // 發生錯誤時回傳錯誤訊息
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
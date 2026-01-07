<?php

require 'conn.php';

try {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        // 取得單筆資料
        
        $id = $_GET['id'];
        
        // ★ 注意：這裡改成撈全部欄位 *，確保編輯時能拿到完整資料 (包含頭貼、詳細資訊等)
        // 請確認你的主鍵名稱是 member_ID
        $sql = "SELECT * FROM member WHERE member_ID = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        
        // 只抓一筆，回傳 Object
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo json_encode($row);
        } else {
            echo json_encode(['error' => '查無此會員']);
        }

    } else {

        // 模式二：取得全部列表
        $sql = 'SELECT 
            member_ID, 
            `name`, 
            email, 
            createdate, 
            `role`, 
            `status` 
            FROM member 
            ORDER BY createdate DESC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        // 抓全部，回傳 Array
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
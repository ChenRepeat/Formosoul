<?php
require 'conn.php';

// 1. 接收並檢查 ID
$orderId = $_GET['id'] ?? 0;

if (!$orderId) {
    echo json_encode(['error' => 'No ID provided']);
    exit;
}

try {
    // --- 第一步：查訂單主檔 (Info) ---
    $stmt = $pdo->prepare("SELECT * FROM `order` WHERE order_ID = ?");
    $stmt->execute([$orderId]);
    $orderInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$orderInfo) {
        echo json_encode(['error' => 'Order not found']);
        exit;
    }

    // 查訂單明細 (Items) ---
    $sqlItems = "
        SELECT 
            od.order_detail_ID, 
            od.quantity, 
            od.price,
            p.name_en, 
            SUBSTRING_INDEX(p.image, '|', 1) as url 
        FROM order_detail od
        JOIN `product` p ON od.product_ID = p.product_ID
        WHERE od.order_ID = ?
    ";
    
    $stmtItems = $pdo->prepare($sqlItems);
    $stmtItems->execute([$orderId]);
    $orderItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    // --- 第三步：組合資料 ---
    $response = [
        'info' => $orderInfo,
        'items' => $orderItems
    ];

    echo json_encode($response);

} catch (Exception $e) {
    // 捕捉錯誤並回傳 JSON
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
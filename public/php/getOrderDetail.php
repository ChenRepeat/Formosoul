<?php
require 'conn.php';

// 1. 接收並檢查 ID (這裡接收到的是訂單編號字串，例如 'ORD20260107...')
$orderId = $_GET['id'] ?? '';

if (!$orderId) {
    echo json_encode(['error' => 'No ID provided']);
    exit;
}

try {
    // --- 第一步：查訂單主檔 (Info) ---
    // ★ 修改：因為前端傳來的是 order_number，所以 WHERE 要改查 order_number
    $stmt = $pdo->prepare("SELECT * FROM `order` WHERE order_number = ?");
    $stmt->execute([$orderId]);
    $orderInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$orderInfo) {
        echo json_encode(['error' => 'Order not found']);
        exit;
    }

    // --- 第二步：查訂單明細 (Items) ---
    // ★ 修改：明細表要用 order_no 來對應主表的 order_number
    $sqlItems = "
        SELECT 
            od.order_detail_ID, 
            od.quantity, 
            od.price,
            p.name_en, 
            p.name_zh,
            -- 取出第一張圖片
            SUBSTRING_INDEX(p.image, '|', 1) as url 
        FROM order_detail od
        JOIN `product` p ON od.product_ID = p.product_ID
        WHERE od.order_no = ?
    ";
    
    // 直接使用傳進來的 $orderId (即訂單編號) 進行查詢
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
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
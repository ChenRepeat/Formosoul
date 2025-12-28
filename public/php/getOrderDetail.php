<?php
  require_once 'conn.php';

$orderId = $_GET['id'] ?? 0;

if (!$orderId) {
    echo json_encode(['error' => 'No ID provided']);
    exit;
}

    $stmt = $pdo->prepare("SELECT * FROM `order` WHERE order_ID = ?");
    $stmt->execute([$orderId]);
    $orderInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$orderInfo) {
        echo json_encode(['error' => 'Order not found']);
        exit;
    }

    // --- 第二步：查訂單明細 (Items) ---
    // 這裡查 order_details 表，並關聯 product 表拿圖片和名稱
    $sqlItems = "
        SELECT 
            od.order_detail_ID, od.quantity, od.price,p.name_en, img.url 
        FROM order_detail od
        JOIN `product` p ON od.product_ID = p.product_ID
        LEFT JOIN 
            `product_images` img ON p.product_ID = img.product_ID
        WHERE od.order_ID = ? AND img.is_main = 1
    ";
    $stmtItems = $pdo->prepare($sqlItems);
    $stmtItems->execute([$orderId]);
    $orderItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    // --- 第三步：組合資料 ---
    // 這是最關鍵的一步，把兩個結果包在一起
    $response = [
        'info' => $orderInfo,   // 這是一個物件 {}
        'items' => $orderItems  // 這是一個陣列 []
    ];

    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($response);

?>
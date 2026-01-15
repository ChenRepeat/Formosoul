<?php

require 'conn.php';

$response = ['success' => false, 'message' => ''];

try {
    // 自動判斷連線變數
    if (isset($conn)) {
        $db = $conn;
    } elseif (isset($pdo)) {
        $db = $pdo;
    } else {
        throw new Exception("無法取得資料庫連線變數");
    }

    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    if (!$input || !isset($input['orderId'])) {
        throw new Exception('無效的參數');
    }

    $orderId = $input['orderId']; // 這裡是 'ORD2026...' 字串

    // ====================================================
    // ★ 修改這裡：把 WHERE order_ID 改成 WHERE order_number
    // 因為 order_ID 是數字，order_number 才是字串
    // ====================================================
    $sql = "UPDATE `order` SET 
            name_en = ?, 
            phone = ?, 
            address_en = ?, 
            remark = ?,
            status = ? 
            WHERE order_number = ?";  // <--- 這裡改了！

    $statusValue = 1; 
    if (isset($input['status'])) {
        $statusValue = $input['status'];
    } elseif (isset($input['isCancel']) && $input['isCancel'] === true) {
        $statusValue = 4;
    }

    $params = [
        $input['recipientName'] ?? '',
        $input['phone'] ?? '',          
        $input['address'] ?? '',
        $input['cancelReason'] ?? '',
        $statusValue,
        $orderId // 傳入 'ORD...' 字串，現在會對應到 order_number
    ];

    $stmt = $db->prepare($sql);
    $result = $stmt->execute($params);

    if ($result) {
        $response['success'] = true;
        $response['message'] = '訂單更新成功';
    } else {
        throw new Exception('資料庫更新執行失敗');
    }

} catch (Throwable $e) {
    http_response_code(500);
    $response['message'] = '更新失敗: ' . $e->getMessage();
    $response['debug_trace'] = $e->getTraceAsString();
}

echo json_encode($response);
?>
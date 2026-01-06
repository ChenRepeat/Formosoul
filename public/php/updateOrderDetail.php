<?php
require 'conn.php';

$response = ['success' => false, 'message' => ''];

try {
    // ====================================================
    // 1. 接收前端傳來的 JSON 資料
    // ====================================================
    // 因為 Vue 使用 JSON.stringify，所以不能用 $_POST，要讀取 input stream
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true); // 轉成關聯陣列

    // 檢查是否有收到資料
    if (!$input) {
        throw new Exception('未接收到 JSON 資料');
    }

    // 檢查必要欄位 (訂單 ID)
    if (!isset($input['orderId']) || empty($input['orderId'])) {
        throw new Exception('缺少訂單 ID');
    }

    $orderId = $input['orderId'];

    $sql = "UPDATE `order` SET 
            name_en = ?, 
            phone = ?, 
            address_en = ?, 
            remark = ? ";

    $params = [
        $input['recipientName'] ?? '',
        $input['phone'] ?? '',          
        $input['address'] ?? '',
        $input['cancelReason'] ?? ''
    ];

    // 處理「取消訂單」邏輯
    if (isset($input['isCancel']) && $input['isCancel'] === true) {
        $sql .= ", status = 4 "; 
    }
    // 如果 isCancel 為 false，我們就不動 status 欄位，保持原本的狀態 (如已付款、已出貨等)

    // 最後加上 WHERE 條件
    $sql .= "WHERE order_ID = ?";
    $params[] = $orderId; // 把 ID 加入參數陣列的最後

    // ====================================================
    // 4. 執行更新
    // ====================================================
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($params);

    if ($result) {
        $response['success'] = true;
        $response['message'] = '訂單更新成功';
    } else {
        throw new Exception('資料庫更新執行失敗');
    }

} catch (Exception $e) {
    http_response_code(500);
    $response['message'] = '更新失敗: ' . $e->getMessage();
}

echo json_encode($response);
?>
<?php
// ★★★ 先設定 CORS,確保無論如何都有 header ★★★
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); 
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=utf-8'); // 改成 utf-8

// 處理 OPTIONS 預檢
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 開啟錯誤報告
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'conn.php'; 

// 初始化回應陣列
$response = ['success' => false, 'message' => '未知的錯誤'];

try {
    // 3. 接收 JSON 資料
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    // 除錯：確認真的有收到 JSON，不然就是前端沒送過來
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON 解析失敗，前端可能未傳送 JSON');
    }

    if (!$input) {
        throw new Exception('沒有收到任何輸入資料');
    }

    // 4. 檢查必要欄位 ID
    if (empty($input['orderId'])) {
        throw new Exception('未提供訂單 ID (orderId)');
    }

    $orderId = $input['orderId'];

    // 5. 接收欄位 (使用 ?? '' 防止未定義警告)
    // 欄位名稱必須跟 Vue 前端送出的 payload 一模一樣
    $nameZh = $input['recipientNameZh'] ?? '';
    $nameEn = $input['recipientNameEn'] ?? '';
    
    // ★ 強制轉字串，避免前端傳數字 912... 導致 0 被吃掉
    $phone  = (string)($input['phone'] ?? ''); 
    
    $addrZh = $input['addressZh'] ?? '';
    $addrEn = $input['addressEn'] ?? '';
    
    $isCancel = $input['isCancel'] ?? false;
    $cancelReason = $input['cancelReason'] ?? '';

    // 6. 準備 SQL
    // 請確認你的資料表名稱真的是 `order` (反引號很重要)
    if ($isCancel) {
        // --- 情況 A：取消訂單 ---
        $sql = "UPDATE `order` SET 
                name_zh = ?, 
                name_en = ?, 
                phone = ?, 
                address_zh = ?, 
                address_en = ?,
                status = 4, 
                remark = ? 
                WHERE order_ID = ?";
        
        $params = [$nameZh, $nameEn, $phone, $addrZh, $addrEn, $cancelReason, $orderId];
    } else {
        // --- 情況 B：純修改資料 ---
        $sql = "UPDATE `order` SET 
                name_zh = ?, 
                name_en = ?, 
                phone = ?, 
                address_zh = ?, 
                address_en = ?
                WHERE order_ID = ?";
        
        $params = [$nameZh, $nameEn, $phone, $addrZh, $addrEn, $orderId];
    }

    // 7. 執行更新
    // $pdo 物件來自 conn.php
    if (!isset($pdo)) {
        throw new Exception('$pdo 物件不存在，請檢查 conn.php 是否正確建立連線');
    }

    $stmt = $pdo->prepare($sql);
    
    // 捕捉 SQL 執行錯誤
    if ($stmt->execute($params)) {
        $response['success'] = true;
        $response['message'] = '訂單更新成功';
    } else {
        $errorInfo = $stmt->errorInfo();
        throw new Exception('SQL 執行失敗: ' . $errorInfo[2]);
    }

} catch (Exception $e) {
    // 這裡不用設定 header 500，回傳 200 但 success: false 給前端處理錯誤訊息比較溫和
    // 或者你要維持 500 也可以： http_response_code(500);
    $response['success'] = false;
    $response['message'] = '處理失敗: ' . $e->getMessage();
}

// 8. 輸出結果
// 這是最重要的部分，如果沒有這行，前端就會收到「空白回應」
echo json_encode($response);
?>
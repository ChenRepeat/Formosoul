<?php
require 'conn.php';

$response = ['success' => false, 'message' => ''];

try {
    // 0. 檢查 ID 是否存在
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception('缺少優惠券 ID (coupons_ID)');
    }

    $id = $_POST['id'];
    
    // 1. 檢查必要欄位 (可視需求調整，例如名稱必填)
    if (empty($_POST['name'])) {
        throw new Exception('必須填寫優惠券名稱');
    }

    // ====================================================
    // 2. 準備變數 (強制轉型，防止空字串報錯)
    // ====================================================
    $name = $_POST['name'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];

    // 折扣金額
    $discountVal = isset($_POST['discount']) ? $_POST['discount'] : 0;
    $discount = floatval($discountVal); 

    // 門檻
    $thresholdVal = isset($_POST['threshold']) ? $_POST['threshold'] : 0;
    $threshold = floatval($thresholdVal);

    // 發行張數
    $countVal = isset($_POST['count']) && $_POST['count'] !== '' ? $_POST['count'] : 100;
    $count = intval($countVal);

    // 狀態
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
    
    // 類型 (若資料庫有加 type 欄位)
    $type = isset($_POST['type']) ? $_POST['type'] : 'amount';

    // ====================================================
    // 3. 執行 UPDATE
    // ====================================================
    
    // 請根據你的資料庫結構選擇合適的 SQL
    // 如果資料庫有 `type` 欄位：
    /*
    $sql = "UPDATE coupons SET 
            name = ?,
            type = ?,
            discount = ?,
            threshold = ?,
            startdate = ?,
            enddate = ?,
            count = ?,
            status = ?
            WHERE coupons_ID = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $name,
        $type,
        $discount,
        $threshold,
        $startdate,
        $enddate,
        $count,
        $status,
        $id
    ]);
    */

    // 如果資料庫 **沒有** `type` 欄位 (維持現狀)：
    $sql = "UPDATE coupons SET 
            name = ?,
            discount = ?,
            threshold = ?,
            startdate = ?,
            enddate = ?,
            count = ?,
            status = ?
            WHERE coupons_ID = ?"; // 注意主鍵名稱是否為 coupons_ID

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $name,
        $discount,
        $threshold,
        $startdate,
        $enddate,
        $count,
        $status,
        $id
    ]);

    $response['success'] = true;
    $response['message'] = '優惠券更新成功';

} catch (Exception $e) {
    // 500 錯誤碼會讓前端 fetch 進到 catch 區塊，若想在 then 裡面處理 success: false，可以拿掉這行
    // http_response_code(500); 
    $response['message'] = '更新失敗: ' . $e->getMessage();
}

echo json_encode($response);
?>
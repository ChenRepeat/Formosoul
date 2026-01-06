<?php
require 'conn.php';

try {
    // 1. 基本檢查：名稱必填
    if (empty($_POST['name'])) {
        throw new Exception('必須填寫優惠券名稱');
    }
    
    // 2. 檢查日期是否存在
    if (!isset($_POST['startdate']) || !isset($_POST['enddate'])) {
        throw new Exception('日期欄位缺漏');
    }

    // =================================================================
    // ★★★ 核心修正區：強制轉型為數字 ★★★
    // =================================================================
    
    // 接收變數
    $name = $_POST['name'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];

    // 處理 Discount (折扣金額)
    // 邏輯：不管收到什麼，先用 floatval() 強制轉成數字。
    // 如果是空字串 "" -> 會變成 0
    // 如果是 "undefined" -> 會變成 0
    // 如果是 "100" -> 會變成 100
    $discountVal = isset($_POST['discount']) ? $_POST['discount'] : 0;
    $discount = floatval($discountVal); 

    // 處理 Threshold (門檻)
    $thresholdVal = isset($_POST['threshold']) ? $_POST['threshold'] : 0;
    $threshold = floatval($thresholdVal);

    // 處理 Count (張數)，預設 100
    $countVal = isset($_POST['count']) && $_POST['count'] !== '' ? $_POST['count'] : 100;
    $count = intval($countVal);

    // 處理 Status (狀態)
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
    
    // 處理 Requirement (文字欄位，允許 null 或空字串)
    $requirement = isset($_POST['requirement']) ? $_POST['requirement'] : null;

    // =================================================================
    // 執行 SQL
    // =================================================================
    
    // 請確認你的資料庫欄位。如果沒有 `type`，請用這段：
    $sqlCoupon = "INSERT INTO coupons (
        name, discount, threshold, startdate, enddate, count, status, requirement
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?
    )";

    $stmt = $pdo->prepare($sqlCoupon);
    
    $stmt->execute([
        $name,
        $discount,  // 這裡現在保證是數字 (例如 0)，絕對不會是字串 ''
        $threshold, // 這裡也保證是數字
        $startdate,
        $enddate,
        $count,
        $status,
        $requirement
    ]);

    $newCouponID = $pdo->lastInsertId();

    echo json_encode([
        'success' => true, 
        'message' => '新增優惠券成功',
        'id' => $newCouponID
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => '處理失敗: ' . $e->getMessage()]);
}
?>
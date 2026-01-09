<?php
// 1. 載入必要檔案
require_once 'conn.php';
require_once 'ECPay.Payment.Integration.php';

try {
    // 2. 建立綠界物件
    $obj = new ECPay_AllInOne();

    // ⚠️ 注意：這裡的 HashKey 與 HashIV 必須跟 ecpay.php 一模一樣
    // 如果你是測試帳號，就用下面這組；如果是正式帳號，記得換掉
    $obj->HashKey     = 'pwFHCqoQZGmho4w6'; 
    $obj->HashIV      = 'EkRm7iFT261dpevs';
    $obj->EncryptType = '1';

    // 3. 取得綠界回傳的資料並驗證 (CheckOutFeedback)
    // 這一行非常神奇，它會自動：
    // a. 抓取 $_POST 資料
    // b. 計算檢查碼 (CheckMacValue)
    // c. 驗證資料有沒有被竄改
    // 如果驗證失敗，SDK 會直接丟出 Exception，程式會跳到 catch
    $feedback = $obj->CheckOutFeedback();

    // ------------------------------------------------------------
    // 程式跑到這裡，代表資料是安全的 (由綠界發出，未被竄改)
    // 接下來要處理我們自己的邏輯
    // ------------------------------------------------------------

    // 4. 取出關鍵資料
    $order_number = $feedback['MerchantTradeNo']; // 訂單編號
    $rtn_code     = $feedback['RtnCode'];         // 交易狀態 (1 代表成功)
    $rtn_msg      = $feedback['RtnMsg'];          // 訊息
    $amount       = $feedback['TradeAmt'];        // 交易金額

    // 5. 判斷交易是否成功
    if ($rtn_code != '1') {
        // 交易失敗 (可能是卡號錯誤、餘額不足...)
        // 你可以更新訂單狀態為失敗，或是不做動作
        // 即使失敗，也要回傳 1|OK 給綠界，表示我們收到通知了，不然它會一直寄
        echo '1|OK';
        exit;
    }

    // 6. 查詢資料庫的原始訂單
    $sql = "SELECT * FROM `order` WHERE order_number = :ord_num";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ord_num' => $order_number]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        // 找不到訂單 (邏輯上不該發生)
        echo '0|Order Not Found';
        exit;
    }

    // 7. 🔥 安全性檢查：金額比對
    // 防止駭客攔截封包，把 1000 元改成 1 元
    if (intval($order['total_amount']) != intval($amount)) {
        echo '0|Amount Mismatch';
        exit;
    }

    // 8. 檢查訂單狀態
    // 如果已經是已付款(例如 1)，就不用再改一次，直接回傳 OK
    // 假設 5 = 未付款, 1 = 已付款
    if ($order['status'] != 5) {
        echo '1|OK';
        exit;
    }

    // 9. 更新資料庫：將訂單改成「已付款」
    // 你也可以順便紀錄 payment_date 或綠界的 TradeNo
    $updateSql = "UPDATE `order` SET status = 1 WHERE order_number = :ord_num";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([':ord_num' => $order_number]);

    // 10. 🎉 最後回應綠界
    // 這是規定格式，絕對不能改，也不能有空格
    echo '1|OK';

} catch (Exception $e) {
    // 驗證失敗 (可能是偽造的請求)
    // 寫入 Log 供檢查 (建議)
    file_put_contents('ecpay_error.log', date('Y-m-d H:i:s') . ' - ' . $e->getMessage() . "\n", FILE_APPEND);
    echo '0|' . $e->getMessage();
}


?>

<?php

// 1. 連線  ----------
require_once 'conn.php';
// 環境設定
// 因為本來 conn.php 的設定為 JSON 格式，放的位置需要在載入 conn.php 後
header("Content-Type: text/html; charset=utf-8");

// 需確保這支 SDK 檔案已經放在同一個資料夾內
require_once 'ECPay.Payment.Integration.php'; 


// 2. 接收訂單編號 (來自 Vue 的網址傳參 ?order_number=xxx)  ----------
$order_number = $_GET['order_number'] ?? null;


if (!$order_number) {
    die("錯誤：沒有訂單編號");
}

try {
    // 3. 從資料庫撈出這筆訂單的金額  ----------
    $sql = "SELECT * FROM `order` WHERE order_number = :ord_num"; 
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ord_num' => $order_number]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        die("錯誤：找不到該筆訂單 ($order_number)");
    }

    // 4. 準備給綠界的資料  ----------
    // 金額必須是整數 (不能有小數點)
    $total_amount = intval($order['total_amount']); 
    
    // 綠界要求的時間格式 (yyyy/MM/dd HH:mm:ss)
    $trade_date = date('Y/m/d H:i:s');            
    
    // 交易描述 (顯示在綠界刷卡頁面上)
    $desc = "Formosoul 商品一批";          
    
    
    // 5. 動態取得回傳當下的網址  ----------
    // 判斷是 http 還是 https (Ngrok 通常是 https)
    $host = $_SERVER['HTTP_HOST']; // 例如 localhost:5173 或 xxx.ngrok.app
    
    // 5-1. 定義你的網址
    // 🔥 只要改這個變數就好，不用改下面的邏輯
    // Ngrok 網址 (測試階段專用)
    $ngrok_domain = "https://carri-luscious-nanci.ngrok-free.dev"; 
    // tibame 網址 (正式環境)
    $tibame_domain = "https://tibamef2e.com/tjd103";


    // 5-2. 判斷邏輯
    if ($host === 'localhost' || str_starts_with($host, '127.0.0.1')) {
        // [情況 A]：如果你是在本機 localhost 操作
        // 強制把 ReturnURL 換成 Ngrok，這樣綠界才找得到你！
        $baseReturnURL = $ngrok_domain . "/Formosoul/public/php";
    } else {
        // [情況 B]：如果你是透過 Ngrok 網址開啟，或是未來正式上線
        // 自動抓取當前的網址 (http 或 https 自動判斷)
        // $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        // $baseReturnURL = "$protocol://$host/Formosoul/public/php";
        // https://tibamef2e.com/tjd103/php/ecpay_return.php
        $baseReturnURL = $tibame_domain . "/php";
    }

    // 5-3. 定義跳回網址
    // 🔥 只要改這個變數就好，不用改下面的邏輯
    //定義接收網址
    //local（測試）
    $local_website = "http://localhost:5173";
    // http://localhost:5173/tjd103/shoppingcart/ordersuccess

    //tibame（正式上線）
    $tibame_website = "https://tibamef2e.com";
    // https://tibamef2e.com/tjd103/shoppingcart/ordersuccess


    // 5-2. 判斷邏輯
    if ($host === 'localhost' || str_starts_with($host, '127.0.0.1')) {
        $baseShowURL = $local_website . "/tjd103/shoppingcart/ordersuccess";
    } else {
        $baseShowURL = $tibame_website . "/tjd103/shoppingcart/ordersuccess";
    }




    
    // 6. 呼叫綠界 SDK 產生表單  ----------

    $obj = new ECPay_AllInOne();

    // --- 測試環境設定 (正式上線時要改成正式帳號) ---
    $obj->ServiceURL  = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5"; // 測試網址
    $obj->HashKey     = "pwFHCqoQZGmho4w6"; // 綠界通用測試 Key (不用改)
    $obj->HashIV      = "EkRm7iFT261dpevs"; // 綠界通用測試 IV  (不用改)
    $obj->MerchantID  = "3002607";          // 綠界通用測試 ID  (不用改)
    $obj->EncryptType = '1';                // 加密模式固定為 1 (SHA256)

    // --- 訂單參數設定 ---
    
    // [ReturnURL] 重要！
    // 付款完成後，綠界伺服器會「背景」通知這支 PHP (Server to Server)
    // 注意：localhost 綠界連不到，測試要用 Ngrok
    //$obj->Send['ReturnURL'] = "https://carri-luscious-nanci.ngrok-free.dev/Formosoul/public/php/ecpay_return.php"; 
    $obj->Send['ReturnURL'] = $baseReturnURL . "/ecpay_return.php";


    
    
    // [ClientBackURL]
    // 付款完成後，使用者按「返回商店」按鈕會跳轉回哪裡？ (通常是你的 Vue 訂單成功頁)
    // 請確認你的 Vue 本地端 Port 是 5173 還是其他
    $obj->Send['ClientBackURL'] = $baseShowURL; 

    // [OrderResultURL]
    // 如果設定這個，綠界付款完會直接跳轉回來，不顯示綠界成功頁 (通常留空，讓綠界顯示成功頁比較清楚)
    $obj->Send['OrderResultURL'] = ""; 

    $obj->Send['MerchantTradeNo']   = $order['order_number'];   // 訂單編號 (不可重複)
    $obj->Send['MerchantTradeDate'] = $trade_date;              // 交易時間
    $obj->Send['TotalAmount']       = $total_amount;            // 交易金額
    $obj->Send['TradeDesc']         = $desc;                    // 交易描述
    $obj->Send['ChoosePayment']     = ECPay_PaymentMethod::Credit; // 預設付款方式：信用卡
    array_push($obj->Send['Items'], array(
        'Name' => "Formosoul Order " . $order_number, 
        'Price' => $total_amount,
        'Currency' => "TWD",
        'Quantity' => 1,
        'URL' => "def"
    ));                                                            //商品名稱

    // 7. 自動送出 (產生 HTML Form 並自動 Submit)  ----------
    // 執行到這行時，就會跳轉到綠界
    $obj->CheckOut();

} catch (Exception $e) {
    echo "綠界串接失敗：" . $e->getMessage();
}


?>













<?php

// 連線 ----------
require_once 'conn.php';


// 接收訂單資料 <使用 Axios >----------
// true 轉成關聯陣列
$orderInfo = json_decode(file_get_contents('php://input'), true);

// 如果沒收到資料，回傳錯誤訊息
// 因為是回傳給 vue 要轉成 JSON，所以要 json_encode
if (!$orderInfo) {
    echo json_encode([
        'status' => 'error', 
        'message' => '沒收到資料']);
    exit;
}


// 產生唯一的訂單編號 ex: ORD2026010413q0er9----------
function createOrderID(){
    $prefix = "ORD";
    // 抓取時間到年月日小時，實際可以取到秒 'YmdHis'
    $orderDate = date('YmdH');      
    // 抓亂數，從0開始抓5個位數
    $random = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"), 0, 5);;
    return = $prefix . $orderDate . $random;
}

$orderID = createOrderID();


// SQL ----------
// 使用 try{ }catch{ } ：如果 try 的動作有報錯，網頁不會當機，而是會跳到 catch，執行 catch 區塊的動作
// 交易模式：裡面的動作要都完成才算是成功，如果動作卡住，要恢復到完全未執行的狀態，才不會有 bug
try{
    //step1 開啟交易模式
    $pdo->beginTransaction();

    //step2 寫入主訂單 order
    $sql_order = "INSERT INTO order
                (order_number, date, payment, status, shipping, member_ID, address_en, coupons_ID, phone, name_en, remark)
                VALUES
                (:o_num, NOW(), :pay, 5, :ship, :m_ID, :addr, :c_ID, :phone, :r_name, :remark)";



    //step3 寫入訂單明細 order_detail
    $sql_orderDetail = "INSERT INTO order_detail
                        (order_no, product_ID, quantity, price)
                        VALUES
                        (:o_num, :p_ID, :qty, :price)";





}catch(Exception $e){

}






?>
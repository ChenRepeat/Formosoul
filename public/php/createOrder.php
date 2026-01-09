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
    $random = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"), 0, 5);
    return $prefix.$orderDate.$random;
}

$orderID = createOrderID();


// SQL ----------
// 使用 try{ }catch{ } ：如果 try 的動作有報錯，網頁不會當機，而是會跳到 catch，執行 catch 區塊的動作
// 交易模式：裡面的動作要都完成才算是成功，如果動作卡住，要恢復到完全未執行的狀態，才不會有 bug
try{
    //step1 <<開啟交易模式>> ----------
    $pdo->beginTransaction();
    $finalTotalAmount = 0;
    


    //step2 <<準備 / 計算相關欄位資料>> ----------
    
    //unit sumprice ＆ total sum price ＝＝＝
    $totalSum = 0;
    $itemDetails = [];

    //先用 product_ID 查出需要的欄位
    //加入 FOR UPDATE 是為了避免有人同時買這項商品時，庫存會有出入，所以先把這一列資料鎖定，等流程跑完才開放下一位查詢
    //查詢商品資訊字串
    $stmtProduct = $pdo -> prepare("SELECT price, name_en, name_zh, stock FROM product WHERE product_ID = ? FOR UPDATE");

    //處理庫存的字串
    $stmtDeductStock = $pdo -> prepare("UPDATE product SET stock = stock - ? WHERE product_ID = ?");

    //處理庫存為 0 下架的字串
    $stmtUnListed = $pdo -> prepare("UPDATE product SET product_status = 0 WHERE product_ID = ?");

    //開始處理購物車商品資料
    foreach($orderInfo['cartItem'] as $unitItem){

        //stepA 先確認商品數量有沒有異常（變成負數）******
        $quantity = (int)$unitItem['quantity'];

        if($quantity <= 0){
            throw new Exception("商品數量異常");
        }

        //stepB 開始查詢商品  // execute() 只能傳陣列 ******
        $stmtProduct -> execute([$unitItem['product_ID']]);
        $dbProduct = $stmtProduct -> fetch(PDO::FETCH_ASSOC);       // 抓資料封裝成一個陣列，因為逐一查詢，所以每次只有一筆，所以用fetch()----------

        if (!$dbProduct) {
            //寫法一
            //throw new Exception("找不到商品 ID: " . $item['product_ID']);
            //寫法二
            throw new Exception("找不到商品 ID: {$item['product_ID']}");
        }

        //stepC 庫存檢查 ******
        $dbStock = $dbProduct['stock'];
        if($quantity > $dbStock){
            throw new Exception("商品 {$dbProduct['name_zh']} 庫存不足 (僅剩 {$dbStock})");
        }

        //stepD 扣除庫存 ，如果扣完數量庫存為 0 ，商品先下架 ******
        $stmtDeductStock -> execute([$quantity, $unitItem['product_ID']]);

        $currentStock = $dbStock - $quantity;
        if($currentStock === 0){
            $stmtUnListed -> execute([$unitItem['product_ID']]);
        }


        //stepE 計算單項商品小計，所有商品小計，準備所有商品資料以便寫出DB ******
        $unitSum = $dbProduct['price'] * $quantity;
        $totalSum += $unitSum;      //一開始宣告過 $totalSum
        //一開始宣告過 $itemDetails
        //因為商品是逐一加進陣列，所以是 $itemDetails[] =，如果寫$itemDetails =，那只會有最後一樣商品在裡面，他會覆蓋掉前面的
        $itemDetails[] = [
            'p_ID' => $unitItem['product_ID'],
            'name_en' => $dbProduct['name_en'],
            'name_zh' => $dbProduct['name_zh'],
            'qty' => $quantity,
            'price' => $dbProduct['price'],
            's_price' => $unitSum
        ];
    }


    
    //shipping fee ＆ ship ＝＝＝
    $shippingList = [
        'taiwan' => 80,
        'japan' => 120,
        'singapore' => 150,
        'netherlands' => 270,
        'ireland' => 490,
    ];

    $country = isset($orderInfo['country']) ? $orderInfo['country'] : 'taiwan';

    $shippingFee = isset($shippingList[$country]) ? $shippingList[$country] : 80;

    $deliveryMethod = isset($orderInfo['shipping']) ? $orderInfo['shipping'] : 'homeDelivery';



    //coupon check & status = 0 & discount ＝＝＝
    $discountVal = 0;
    $couponID = isset($orderInfo['coupons_ID']) ? $orderInfo['coupons_ID'] : null;

    //如果有coupon_ID，需要嚴格檢查：會員確實有這張 coupon 並且還沒使用過(status = 1 或 3)
    if($couponID){
        $sql_couponCheck = "SELECT uc.coupons_ID,  uc.pointscard_ID,
                                    c.discount
                            FROM user_coupons uc
                            JOIN pointscard p ON uc.pointscard_ID = p.pointscard_ID
                            JOIN coupons c ON uc.coupons_ID = c.coupons_ID
                            WHERE p.member_ID = :m_ID
                              AND uc.coupons_ID = :c_ID
                              AND uc.status IN (1, 3); ";

        $stmtCoupon = $pdo -> prepare($sql_couponCheck);
        $stmtCoupon -> execute([
            ':m_ID' => $orderInfo['member_ID'],
            ':c_ID' => $orderInfo['coupons_ID']
        ]); 

        $dbDiscount = $stmtCoupon -> fetch(PDO::FETCH_ASSOC);

        if($dbDiscount){
            $currentPrice = round(($totalSum + $shippingFee) * 0.9);

            if($dbDiscount['discount'] > $currentPrice){
                $discountVal = $currentPrice;
            }else{
                $discountVal = $dbDiscount['discount'];
            }
            //確認可使用，紀錄折扣並將折價券改為已使用(status = 0)

            $updateCoupon = $pdo -> prepare("UPDATE user_coupons SET status = 0 WHERE pointscard_ID = ? AND coupons_ID = ?");
            $updateCoupon -> execute([$dbDiscount['pointscard_ID'], $dbDiscount['coupons_ID']]);
            
        }else{
           // 無法使用時
            throw new Exception("優惠券無效或已使用"); 
        }
    }

    //total amount  ＝＝＝
    $finalTotalAmount = intval($totalSum + $shippingFee - $discountVal);
    if ($finalTotalAmount < 0) $finalTotalAmount = 0;       //訂單總金額不能為負數



    //step3 <<寫入主訂單 order>> ----------
    $sql_order = "INSERT INTO `order`
                (order_number, member_ID, date, status, name_en, phone, address_en, remark, shipping, shipping_fee, coupons_ID, discount, total_amount, payment  )
                VALUES
                (:o_num, :m_ID, NOW(), 5, :r_name, :phone, :addr, :remark, :ship, :ship_fee,  :c_ID, :discount, :t_amount,  :pay )";
    
    $stmtOrder = $pdo -> prepare($sql_order);
    $stmtOrder -> execute([
        ':o_num' => $orderID,
        ':m_ID' => $orderInfo['member_ID'],
        ':r_name' => $orderInfo['name_en'],
        ':phone' => $orderInfo['phone'], 
        ':addr' => $orderInfo['address_en'], 
        ':remark' => $orderInfo['remark'], 
        ':ship' => $deliveryMethod, 
        ':ship_fee' => $shippingFee,  
        ':c_ID' => $couponID, 
        ':discount' => $discountVal, 
        ':t_amount' => $finalTotalAmount,  
        ':pay' => $orderInfo['payment'] 
    ]);



    //step4 寫入訂單明細 <<order_detail>> ----------
    $sql_orderDetail = "INSERT INTO order_detail
                        (order_no, product_ID, name_en, name_zh, quantity, price, price_sum)
                        VALUES
                        (:o_num, :p_ID, :name_en, :name_zh, :qty, :price, :s_price)";

    $stmtDetail = $pdo->prepare($sql_orderDetail);
    foreach ($itemDetails as $row) {
        $stmtDetail->execute([
            ':o_num'    => $orderID,
            ':p_ID'     => $row['p_ID'],
            ':name_en'  => $row['name_en'],
            ':name_zh'  => $row['name_zh'],
            ':qty'      => $row['qty'],
            ':price'    => $row['price'],
            ':s_price'  => $row['s_price']
        ]);
    }


    // step5 <<提交交易 (確認寫入)>> ----------
    $pdo->commit();

    // 訂單寫入成功
    echo json_encode([
        'status' => 'success',
        'orderID' => $orderID,
        'real_total' => $finalTotalAmount,
        'message' => '訂單建立成功'
    ]);


}catch(Exception $e){
    // 發生錯誤，回復到未寫入前的狀態
    $pdo->rollBack();

    // 回傳錯誤訊息給 Vue
    echo json_encode([
        'status' => 'error',
        'message' => '系統錯誤：' . $e->getMessage()
    ]);
}


?>
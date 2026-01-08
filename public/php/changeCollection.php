<?php
// 連線 ----------
require_once 'conn.php';

// 接收資料 <使用 Fetch > ----------
$likeInfo = json_decode(file_get_contents('php://input'), true);

$memberID  = $likeInfo['member_id'] ?? null;
//寫法一 縮寫 (php 7.0後的語法糖)
$productID = $likeInfo['product_id'] ?? null;
//寫法二 三元運算子
//$productId = isset($input['product_id']) ? $input['product_id'] : null;

// 如果沒收到資料，回傳錯誤訊息
// 因為是回傳給 vue 要轉成 JSON，所以要 json_encode
if (!$memberID || !$productID) {
    echo json_encode([
        'status' => 'error', 
        'message' => '缺少 member_id 或 product_id']);
    exit;
}

//開始處理收藏紀錄 ----------
try{

    //step1 先查詢資料庫的紀錄
    $sqlCheck = "SELECT * FROM collection WHERE member_ID = ? AND product_ID = ?";
    $stmt = $pdo -> prepare($sqlCheck);
    $stmt -> execute([$memberID, $productID]);
    $record = $stmt->fetch();  //只會有一筆

    //step2 如果有記錄，修改狀態，如果沒有紀錄，新增紀錄
    if (!$record) {
        $sqlInsert = "INSERT INTO collection (member_ID, product_ID, collect_status) VALUES (?, ?, 1)";
        $stmtInsert = $pdo -> prepare($sqlInsert);
        $stmtInsert -> execute([$memberID, $productID]);
        
        // 回傳成功訊息給 vue
        echo json_encode([
            'status' => 'success', 
            'action' => 'inserted', 
            'current_status' => 1
        ]);

    } else {
        // 切換狀態：1變0，0變1
        $newStatus = ($record['collect_status'] == 1) ? 0 : 1;
        
        $sqlUpdate = "UPDATE collection SET status = ? WHERE member_ID = ? AND product_ID = ?";
        $stmtUpdate = $pdo -> prepare($sqlUpdate);
        $stmtUpdate -> execute([$newStatus, $memberID, $productID]);
        
        // 回傳成功訊息給 vue
        echo json_encode([
            'status' => 'success', 
            'action' => 'updated', 
            'current_status' => $newStatus
        ]);
    }

}catch(Exception $e){
    // 回傳錯誤訊息給 Vue
    echo json_encode([
        'status' => 'error', 
        'message' => 'SQL 錯誤: ' . $e->getMessage()
    ]);

}


?>
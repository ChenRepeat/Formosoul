<?php

require_once 'conn.php';
$member = json_decode(file_get_contents("php://input"), true);

// 等於在 WorkBench 裡面打指令
$sql = '
      SELECT
          order_number,
          date,
          payment,
          status,
          name_en,
          shipping
        FROM formosoul.order
        WHERE member_ID = :member_ID;         
  '; // :後面，一定要對到SQL的column名稱 WHERE member_ID = :member_ID and email = :email; 

   

    $stmt = $pdo->prepare($sql); // 一定這樣寫
    $stmt->bindValue(':member_ID', $member['member_ID']);
    // $stmt->bindValue(':email', $member['email']);
    $stmt->execute();
    $order_data = $stmt->fetchAll(PDO::FETCH_ASSOC);  // 需要回傳資料才要寫這一句，不然可省略
    if ($order_data) {
        echo json_encode([
        'success' => true,
        'data' => $order_data,
        // 'order' =>
      ]);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到訂單']);
    }

?>
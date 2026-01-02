<?php

require_once 'conn.php';
$member = json_decode(file_get_contents("php://input"), true);

$sql = '
    SELECT
      member_ID, 
      mot, 
      shrimp, 
      dice, 
      ring, 
      bue, 
      member_wandcore 
	  from pointscard 
	  where member_ID = :member_ID; 
'; // :member_ID 就是從前端拿到的資料

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':member_ID', $member['member_ID']);
$stmt->execute();
$points_card_data = $stmt->fetch(PDO::FETCH_ASSOC); // 因為只有一行 所以只要fetch就好

if($points_card_data){
  echo json_encode([
    'success' => true,
    'data' => $points_card_data,
  ]);
} else {
  echo json_encode(['success' => false, 'message' => '找不到訂單']);
}

?>

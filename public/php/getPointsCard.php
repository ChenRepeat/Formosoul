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

  $points_card_data['mot'] = (int)$points_card_data['mot'];
    $points_card_data['shrimp'] = (int)$points_card_data['shrimp'];
    $points_card_data['dice'] = (int)$points_card_data['dice'];
    $points_card_data['ring'] = (int)$points_card_data['ring'];
    $points_card_data['bue'] = (int)$points_card_data['bue'];
    $points_card_data['member_wandcore'] = (int)$points_card_data['member_wandcore'];
    
  echo json_encode([
    'success' => true,
    'data' => $points_card_data,
  ]);
} else {
  echo json_encode(['success' => false, 'message' => '找不到訂單']);
}

?>

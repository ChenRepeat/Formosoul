<?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);
  // 事務處理建議改寫在 PHP 中
  $pdo->beginTransaction();
  $getsql = '
      INSERT IGNORE INTO user_coupons (pointscard_ID, coupons_ID, status, received_at)
      SELECT :pointscard_ID, coupons_id, 2, NOW()
      FROM game_coupon_rewards
      WHERE coupons_id = :coupons_id;
  ';

    $checksql = $pdo->prepare($checksql);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    $stmt->bindValue(':coupons_id', $member['coupons_id']);
    $checksql->execute();
    $event_data = $checksql->fetchAll(PDO::FETCH_ASSOC);
    if ($event_data) {
      echo json_encode([
        'success' => true,
        'data' => $event_data
      ]);
    } else {
        echo json_encode(['success' => false, 'message' => '讀取資料失敗']);
    }
?>
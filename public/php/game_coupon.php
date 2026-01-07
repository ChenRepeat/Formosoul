<?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  $pdo->beginTransaction();

  $coupon_found = false;
  $coupons_id = null;
  do {

    $selectsql = '
      SELECT 
      coupons_id 
      FROM coupons 
      ORDER BY RAND() 
      LIMIT 1
    ';

    $selectstmt = $pdo->prepare($selectsql);
    $selectstmt->execute();
    $coupon = $selectstmt->fetch(PDO::FETCH_ASSOC);
    $coupons_id = $coupon['coupons_id'];
    $checksql = '
      SELECT * 
      FROM user_coupons 
      WHERE pointscard_ID = :pointscard_ID AND coupons_ID = :coupons_ID
    ';

    $checkstmt = $pdo->prepare($checksql);
    $checkstmt->bindValue(':pointscard_ID', $member['pointscard_ID']);
    $checkstmt->bindValue(':coupons_ID', $coupons_id);
    $checkstmt->execute();
    $event_data = $checkstmt->fetch(PDO::FETCH_ASSOC);
    if (!$event_data) {
      $coupon_found = true;
    }
  } while(!$coupon_found);

  $insertsql = '
    INSERT IGNORE INTO user_coupons (pointscard_ID, coupons_ID, status, received_at) 
    VALUES (:pointscard_ID, :coupons_ID, 3, NOW())
  ';

  $insertstmt = $pdo->prepare($insertsql);
  $insertstmt->bindValue(':pointscard_ID', $member['pointscard_ID']);
  $insertstmt->bindValue(':coupons_ID', $coupons_id);
  $insertstmt->execute();

  $pdo->commit();

  echo json_encode([
    'success' => true,
    'message' => 'get coupon',
    'coupons_id' => $coupons_id
  ]);
?>
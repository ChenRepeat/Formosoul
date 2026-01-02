<?php
    require_once 'conn.php';
    $member = json_decode(file_get_contents("php://input"), true);
    $sql = '
        UPDATE user_coupons 
        SET status = 0
        WHERE pointscard_ID = :pointscard_ID AND coupons_ID = :coupons_ID;
    ';
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':pointscard_ID', $member['pointscard_ID']);
    $stmt->bindValue(':coupons_ID', $member['coupons_ID']);
    $stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => '改變coupon',
    ]);


 
?>
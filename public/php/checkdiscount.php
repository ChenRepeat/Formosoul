<?php
    require_once 'conn.php';
    $member = json_decode(file_get_contents("php://input"), true);

    $sql = '
        SELECT * 
        FROM view_user_coupons
        WHERE pointscard_ID = :pointscard_ID AND coupons_ID = :coupons_ID;
    ';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':pointscard_ID', $member['pointscard_ID']);
    $stmt->bindValue(':coupons_ID', $member['coupons_ID']);
    $stmt->execute();
    $coupondata = $stmt->fetch(PDO::FETCH_ASSOC); 
    if($coupondata){
        echo json_encode([
            'success' => true,
            'message' => '取得coupondata',
            'data' => $coupondata,
        ]);
    }else{
        echo json_encode([
            'success' => false,
            'message' => '沒找到coupon'
        ]); 
    }



 
?>
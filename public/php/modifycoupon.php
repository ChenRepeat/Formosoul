<?php
    require_once 'conn.php';
    $member = json_decode(file_get_contents("php://input"), true);
    $sql = '
        UPDATE user_coupons 
        SET 
            status = CASE 
                WHEN status = 1 THEN 3 
                ELSE status 
            END
        WHERE pointscard_ID = :pointscard_ID;
    ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':pointscard_ID', $member['pointscard_ID']);
    $stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => 'coupon回復',
    ]);

    // $returnsql = '
    //     SELECT * 
    //     FROM view_user_coupons
    //     WHERE pointscard_ID = :pointscard_ID;
    // ';
    // $returnstmt = $pdo->prepare($returnsql);
    // $returnstmt->bindValue(':pointscard_ID', $member['pointscard_ID']);
    // $returnstmt->execute();
    // $coupondata = $returnstmt->fetchAll(PDO::FETCH_ASSOC); 
    // if($coupondata){
    //     echo json_encode([
    //         'success' => true,
    //         'message' => '改變coupon',
    //         'data' => $coupondata,
    //     ]);
    // }else{
    //     echo json_encode([
    //         'success' => false,
    //         'message' => '沒找到coupon'
    //     ]); 
    // }



 
?>
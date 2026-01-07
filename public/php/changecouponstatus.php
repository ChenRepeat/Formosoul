<?php
    require_once 'conn.php';
    $member = json_decode(file_get_contents("php://input"), true);
    $sql = '
        UPDATE user_coupons 
        SET 
            status = CASE 
                WHEN pointscard_ID = :pointscard_ID AND coupons_ID = :coupons_ID THEN 1  
                WHEN pointscard_ID = :pointscard_ID THEN 3                  
                ELSE status
            END,
            used_at = NOW()
        WHERE pointscard_ID = :pointscard_ID;
    ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':pointscard_ID', $member['pointscard_ID']);
    $stmt->bindValue(':coupons_ID', $member['coupons_ID']);
    $stmt->execute();
        // $sql = '
        //     UPDATE user_coupons 
        //     SET status = 0, used_at = now()
        //     WHERE pointscard_ID = :pointscard_ID AND coupons_ID = :coupons_ID;
        // ';
        // $stmt = $pdo->prepare($sql);
        // $stmt->bindValue(':pointscard_ID', $member['pointscard_ID']);
        // $stmt->bindValue(':coupons_ID', $member['coupons_ID']);
        // $stmt->execute();
        // $changesql = '
        //     UPDATE user_coupons 
        //     SET status = 2, used_at = now()
        //     WHERE pointscard_ID = :pointscard_ID AND coupons_ID = :coupons_ID;
        // ';
        // $changetmt = $pdo->prepare($changesql);
        // $changetmt->bindValue(':pointscard_ID', $member['pointscard_ID']);
        // $changetmt->bindValue(':coupons_ID', $member['coupons_ID']);
        // $changetmt->execute();
    $returnsql = '
        SELECT * 
        FROM view_user_coupons
        WHERE pointscard_ID = :pointscard_ID AND coupons_ID = :coupons_ID;
    ';
    $returnstmt = $pdo->prepare($returnsql);
    $returnstmt->bindValue(':pointscard_ID', $member['pointscard_ID']);
    $returnstmt->bindValue(':coupons_ID', $member['coupons_ID']);
    $returnstmt->execute();
    $coupondata = $returnstmt->fetch(PDO::FETCH_ASSOC); 
    if($coupondata){
        echo json_encode([
            'success' => true,
            'message' => '改變coupon',
            'data' => $coupondata,
        ]);
    }else{
        echo json_encode([
            'success' => false,
            'message' => '沒找到coupon'
        ]); 
    }



 
?>
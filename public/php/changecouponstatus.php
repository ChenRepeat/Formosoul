<?php
    require_once 'conn.php';
    $member = json_decode(file_get_contents("php://input"), true);
    $sql = '
        UPDATE user_coupons 
        SET 
            status = CASE 
                WHEN status = 0 THEN 2 
                WHEN status = 2 THEN 0 
                ELSE status
            END,
            used_at = NOW()
        WHERE pointscard_ID = :pointscard_ID AND coupons_ID = :coupons_ID;
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
        SELECT 
            p.member_ID,
            uc.pointscard_ID,
            uc.coupons_ID,
            uc.status AS user_coupon_status,
            uc.received_at AS user_coupon_receivedate,
            uc.used_at,
            c.discount,
            c.startdate AS coupon_startdate,
            c.enddate AS coupon_enddate,
            c.count AS coupon_count,
            c.status AS coupon_status
        FROM user_coupons uc
        LEFT JOIN coupons c ON uc.coupons_ID = c.coupons_ID
        LEFT JOIN pointscard p ON p.pointscard_ID = uc.pointscard_ID
        WHERE uc.pointscard_ID = :pointscard_ID AND uc.coupons_ID = :coupons_ID;
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
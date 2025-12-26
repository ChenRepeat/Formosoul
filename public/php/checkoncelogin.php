<?php
    require_once 'conn.php';
    $member = json_decode(file_get_contents("php://input"), true);
    $sql = '
        UPDATE member
        SET updatetime = now()
        WHERE member_ID = :member_ID;
    ';
    
    $checkstmt = $pdo->prepare($checksql);
    $checkstmt->bindValue(':member_ID', $member['member_ID']);
    $checkstmt->execute();
    
    $checkstmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'message' => '會員資料異動'
    ]);


 
?>
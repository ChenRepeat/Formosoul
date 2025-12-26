<?php
    require_once 'conn.php';
    $member = json_decode(file_get_contents("php://input"), true);
    $sql = '
        UPDATE member
        SET name = :name
        WHERE member_ID = :member_ID;
    ';
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    $stmt->bindValue(':name', $member['name']);
    $stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => '會員資料異動',
    ]);


 
?>
<?php
    require_once 'conn.php';
    $member = json_decode(file_get_contents("php://input"), true);

    $checksql = '
        SELECT password  
        FROM member 
        WHERE member_ID = :member_ID;
    ';

    $checkstmt = $pdo->prepare($checksql);
    $checkstmt->bindValue(':member_ID', $member['member_ID']);
    $checkstmt->execute();
    // fetchColumn 可以只拿單一欄位
    $db_password  = $checkstmt->fetchColumn();
    if($db_password == $member['oldpassword']){
        $sql = '
            UPDATE member
            SET password = :password
            WHERE member_ID = :member_ID;
        ';
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':member_ID', $member['member_ID']);
        $stmt->bindValue(':password', $member['Newpassword']);
        if($stmt->execute()){
        echo json_encode([
            'success' => true,
            'message' => 'Password updated successfully',
        ]);

        }else{
        echo json_encode([
            'success' => false,
            'message' => 'Password updated failed.'
            ]);
        exit;
        
        }
    }else{
        echo json_encode([
            'success' => false,
            'message' => 'Old password incorrect.'
        ]);
    }
 
?>
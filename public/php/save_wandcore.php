<?php
    require_once 'conn.php';
    $member = json_decode(file_get_contents("php://input"), true);
    $checksql = '
        SELECT wandcore_ID 
        FROM `member` 
        WHERE member_ID = :member_ID
    ';
    $checkstmt = $pdo->prepare($checksql);
    $checkstmt->bindValue(':member_ID', $member['member_ID']);
    $checkstmt->execute();
    $existingData = $checkstmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($existingData['wandcore_ID'])) {
        echo json_encode([
            'success' => false,
            'message' => '你已經抽過魔杖了，不能重複抽取！'
        ]);
        exit;
    }
    $sql = '
            UPDATE `member` 
            SET wandcore_ID = :wandcore_ID 
            WHERE member_ID = :member_ID;
    ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':wandcore_ID', $member['wandcore_ID']);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    if ($member['member_ID'] && $member['wandcore_ID']) {
        $stmt->execute();
        echo json_encode([
        'success' => true,
        'message' => 'update wandcore'
    ]);
    }else {
        echo json_encode(['success' => false, 'message' => 'no wandcore']);
    }


?>
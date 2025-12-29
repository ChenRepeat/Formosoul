<?php
    require_once 'conn.php';
    $member = json_decode(file_get_contents("php://input"), true);
    $sql = '
        UPDATE member
        SET name = :name,
            headshot = :headshot
        WHERE member_ID = :member_ID;
    ';
    $imgData = $member['tempimg']; // 對應前端傳送的 key
    if (preg_match('/^data:image\/(\w+);base64,/', $imgData, $type)) {
        $imgData = substr($imgData, strpos($imgData, ',') + 1);
        $imgData = base64_decode($imgData);
    } else {
        // 如果不是 base64 格式（例如原本就是路徑），則不處理或做其他邏輯
        $imgData = $member['tempimg']; 
    }
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    $stmt->bindValue(':headshot', $imgData);
    $stmt->bindValue(':name', $member['name']);
    $stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => '會員資料異動',
    ]);


 
?>
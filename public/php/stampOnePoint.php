<?php

require_once 'conn.php';

$input = json_decode(file_get_contents("php://input"), true);

$member_ID = $input['member_ID'] ?? null;
$column = $input['column'] ?? null;

$allowColumns = ['dice', 'shrimp', 'mot', 'ring', 'bue', 'member_wandcore'];

if (!$member_ID || !in_array($column, $allowColumns)) {
    echo json_encode(['success' => false, 'message' => '無效請求']);
    exit();
}

try {
    // 修正重點：使用雙引號才能正確解析 $column 變數
    $sql = "UPDATE pointscard SET $column = 1 WHERE member_ID = :member_ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':member_ID', $member_ID, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => '蓋章成功']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '資料庫錯誤：' . $e->getMessage()]);
}
  // fetch() 是用來「取出」資料（針對 SELECT），而 UPDATE、INSERT 或 DELETE 是「執行」變動，不需要寫fetch ()

?>
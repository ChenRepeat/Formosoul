<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'conn.php';
$input = json_decode(file_get_contents("php://input"), true);

$member_ID = $input['member_ID'] ?? null;
$column = $input['column'] ?? null;

$allowColumns = ['dice', 'shrimp', 'mot', 'ring', 'bue', 'member_wandcore'];

if(!$member_ID || !in_array($column, $allowColumns)) {
  echo json_encode(['success'=> false, 'message'=>'無效請求']);
  exit();
}

try{
  $sql = "
        UPDATE
         formosoul.pointscard
        SET
         $column = 1
        Where
         member_ID = :member_ID";
  
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':member_ID', $member_ID, PDO::PARAM_INT);
  $stmt->execute();
  // fetch() 是用來「取出」資料（針對 SELECT），而 UPDATE、INSERT 或 DELETE 是「執行」變動，不需要寫fetch ()

  if($stmt->rowCount() >= 0){
    echo json_encode(['success'=> true, 'message'=> '蓋章成功'])
  } else {
    echo json_encode(['sucess' => false, 'message'=>'未變更或找不到成員'])
  } 
} catch (PDOException $e) {
    // 捕捉 PDO 錯誤
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>
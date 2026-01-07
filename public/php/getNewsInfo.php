<?php

require_once 'conn.php';
$news = json_decode(file_get_contents("php://input"), true);
 
$sql = "SELECT * FROM news ORDER BY createdate DESC";

// query 準備+執行
$news_data = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json'); 

if($news_data){
  echo json_encode([
    'success' => true,
    'data' => $news_data
  ]);
} else {
  echo json_encode(['success' => false,
  'message' => '找不到資料']);
}

?>

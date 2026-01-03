<?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  
  $sql = '
    SELECT 
      annalevent_ID, title_zh, title_en, pic, content_zh, content_en, content_summary_zh, content_summary_en, video, launchdate, status
    FROM annalevent 
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $event_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($event_data) {
      echo json_encode([
        'success' => true,
        'data' => $event_data
      ]);
    } else {
        echo json_encode(['success' => false, 'message' => '讀取資料失敗']);
    }
?>
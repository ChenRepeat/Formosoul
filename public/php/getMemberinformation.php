  <?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  $sql = '
    SELECT 
      m.name,
      m.member_ID,
      m.createdate,
      m.pointscard,
      w.magical_en,
      w.magical_zh
    FROM member m
    LEFT JOIN wandcore w ON m.wandcore_ID = w.wandcore_ID
    WHERE m.name = :name;
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $member['name']);
    $stmt->execute();
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json; charset=utf-8');
    if ($user_data) {
        echo json_encode(['success' => true, 'data' => $user_data]);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到會員']);
    }



  ?>
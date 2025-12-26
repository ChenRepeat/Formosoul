  <?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  $sql = '
    SELECT 
      m.name,
      m.member_ID,
      m.createdate,
      m.updatetime,
      m.pointscard,
      w.magical_en,
      w.magical_zh
    FROM member m
    LEFT JOIN wandcore w ON m.wandcore_ID = w.wandcore_ID
    WHERE m.member_ID = :member_ID;
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    $stmt->execute();
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user_data) {
        echo json_encode([
        'success' => true,
        'data' => $user_data,
      ]);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到會員']);
    }



  ?>
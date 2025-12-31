  <?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  $sql = '
    SELECT 
      m.name,
      m.member_ID,
      DATE_FORMAT(m.createdate, \'%Y-%m-%d\') AS createdate,
      m.updatetime,
      m.pointscard,
      m.headshot,
      w.name_en,
      w.name_zh
    FROM member m
    LEFT JOIN wandcore w ON m.wandcore_ID = w.wandcore_ID
    WHERE m.member_ID = :member_ID;
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    $stmt->execute();
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user_data) {
      $rawHeadshot = $user_data['headshot'];
        if (filter_var($rawHeadshot, FILTER_VALIDATE_URL)) {
            $user_data['headshot'] = $rawHeadshot;
        } elseif (!empty($rawHeadshot)) {
            $user_data['headshot'] = 'data:image/png;base64,' . base64_encode($rawHeadshot);
        } else {
            $user_data['headshot'] = '';
        }
        echo json_encode([
        'success' => true,
        'data' => $user_data,
        'img' => $rawHeadshot
      ]);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到會員']);
    }



  ?>
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
      w.name_zh,
      bg.buegame_count,
      bg.buegame_pass,
      cg.charmgame_img1,
      dg.dicegame_count,
      dg.dicegame_pass,
      mg.motorcyclegame_count,
      mg.motorcyclegame_score,
      mg.motorcyclegame_pass,
      rg.ringgame_count,
      rg.ringgame_score,
      rg.ringgame_pass,
      sg.shrimpgame_count,
      sg.shrimpgame_score,
      sg.shrimpgame_pass
    FROM formosoul.member m
    LEFT JOIN formosoul.wandcore w ON m.wandcore_ID = w.wandcore_ID
    LEFT JOIN formosoul.pointscard p ON p.member_ID = m.member_ID
    LEFT JOIN formosoul.buegame bg ON bg.pointscard_ID = p.pointscard_ID
    LEFT JOIN formosoul.charmgame cg ON cg.member_ID = m.member_ID
    LEFT JOIN formosoul.dicegame dg ON dg.pointscard_ID = p.pointscard_ID
    LEFT JOIN formosoul.motorcyclegame mg ON mg.pointscard_ID = p.pointscard_ID
    LEFT JOIN formosoul.ringgame rg ON rg.pointscard_ID = p.pointscard_ID
    LEFT JOIN formosoul.shrimpgame sg ON sg.pointscard_ID = p.pointscard_ID
    WHERE m.member_ID = :member_ID;
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    $stmt->execute();
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user_data) {
      $user_data['headshot'] = 'data:image/jpeg;base64,' . base64_encode($user_data['headshot']);
        echo json_encode([
        'success' => true,
        'data' => $user_data,
        'img' => $user_data['headshot']
      ]);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到會員']);
    }



  ?>
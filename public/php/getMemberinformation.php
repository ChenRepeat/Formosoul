<?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  $sql = '
    SELECT 
      m.name,
      m.member_ID,
      DATE_FORMAT(m.createdate, "%Y-%m-%d") AS createdate,
      m.updatetime,
      m.headshot,
      m.role,
      p.pointscard_ID,
      w.nameEn,
      w.nameZh,
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
    FROM member m
    LEFT JOIN wandcore w ON m.wandcore_ID = w.wandcore_ID
    LEFT JOIN pointscard p ON p.member_ID = m.member_ID
    LEFT JOIN buegame bg ON bg.pointscard_ID = p.pointscard_ID
    LEFT JOIN charmgame cg ON cg.member_ID = m.member_ID
    LEFT JOIN dicegame dg ON dg.pointscard_ID = p.pointscard_ID
    LEFT JOIN motorcyclegame mg ON mg.pointscard_ID = p.pointscard_ID
    LEFT JOIN ringgame rg ON rg.pointscard_ID = p.pointscard_ID
    LEFT JOIN shrimpgame sg ON sg.pointscard_ID = p.pointscard_ID
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
        'data' => $user_data
      ]);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到會員']);
    }



  ?>
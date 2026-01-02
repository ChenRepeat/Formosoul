<?php
  header('Content-Type: application/json; charset=utf-8');
  require_once 'conn.php';

  try {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['pointscard_ID'])) {
        throw new Exception("缺少集點卡卡號");
    }

    if (!isset($input['gameType'])) {
        throw new Exception("缺少遊戲類型");
    }

    // 從前端傳來的參數，並判斷值
    $pid = $input['pointscard_ID']; 
    $mid = $input['member_ID'];
    $gameType = $input['gameType'];
    if (isset($input['score'])) {
        $score = (int)$input['score'];
    } else {
        $score = 0;
    }
    $pass = 0;
    if (isset($input['pass'])) {
        $pass = (int)$input['pass'];
    }
    $img = '';
    if (isset($input['img'])) {
        $img = $input['img'];
    }
    $sql = '';
    
    $validGames = [
      'shrimp'     => true,
      'motorcycle' => true,
      'ring'       => true,
      'bue'        => false,
      'dice'       => false,
    ];

    if ($gameType === 'charm') {
        if (empty($img)) throw new Exception("缺少 img");
        if (empty($mid)) throw new Exception("缺少 member_ID");
        $sql = "UPDATE charmgame SET charmgame_img1 = :img WHERE member_ID = :mid";
    } 
    // 通用SQL
    elseif (isset($validGames[$gameType])) {
        //  判斷score欄位
        $hasScore = $validGames[$gameType]; 
        $sql = "UPDATE {$gameType}game SET ";
        if ($hasScore) {
            $sql .= "{$gameType}game_score = GREATEST({$gameType}game_score, :score), ";
        }
        $sql .= "{$gameType}game_pass = GREATEST({$gameType}game_pass, :pass), ";
        $sql .= "{$gameType}game_count = {$gameType}game_count + 1 ";
        $sql .= "WHERE pointscard_ID = :pid";
    } else {
        throw new Exception("未知的遊戲類型: $gameType");
    }

    $stmt = $pdo->prepare($sql);
      if (strpos($sql, ':pid') !== false) {
        $stmt->bindValue(':pid', $pid);
      }
      if (strpos($sql, ':mid') !== false) {
        $stmt->bindValue(':mid', $mid);
      }
      if (strpos($sql, ':score') !== false) {
          $stmt->bindValue(':score', $score);
      }
      if (strpos($sql, ':img') !== false) {
        $stmt->bindValue(':img', $img);
      }
      if (strpos($sql, ':pass') !== false) {
        $stmt->bindValue(':pass', $pass);
      }
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
          echo json_encode(['success' => true, 'message' => "成績更新成功 ($gameType)"]);
      } else {
          echo json_encode(['success' => false, 'message' => "更新失敗 (ID無效或資料無變更)"]);
      }

  } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  }
?>
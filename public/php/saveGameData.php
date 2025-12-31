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
    
    switch ($gameType) {
          
      //  釣蝦
      case 'shrimp':
        $sql = "UPDATE formosoul.shrimpgame SET 
              shrimpgame_score = GREATEST(shrimpgame_score, :score), 
              shrimpgame_pass  = GREATEST(shrimpgame_pass, :pass),
              shrimpgame_count = shrimpgame_count + 1
              WHERE pointscard_ID = :pid";
      break;

      // 摩托車遊戲
      case 'motor':
        $sql = "UPDATE formosoul.motorcyclegame SET 
                motorcyclegame_score = GREATEST(motorcyclegame_score, :score), 
                motorcyclegame_pass  = GREATEST(motorcyclegame_pass, :pass),
                motorcyclegame_count = motorcyclegame_count + 1
                WHERE pointscard_ID = :pid";
      break;
      
      // 套圈圈
      case 'ring':
        $sql = "UPDATE formosoul.ringgame SET 
                ringgame_score = GREATEST(ringgame_score, :score), 
                ringgame_pass  = GREATEST(ringgame_pass, :pass),
                ringgame_count = ringgame_count + 1
                WHERE pointscard_ID = :pid";
      break;
      
      // 擲筊
      case 'bue':
        $sql = "UPDATE formosoul.buegame SET 
                buegame_pass  = GREATEST(buegame_pass, :pass),
                buegame_count = buegame_count + 1
                WHERE pointscard_ID = :pid";
      break;

      // 骰子
      case 'dice':
        $sql = "UPDATE formosoul.dicegame SET 
                dicegame_pass  = GREATEST(dicegame_pass, :pass),
                dicegame_count = dicegame_count + 1
                WHERE pointscard_ID = :pid";
      break;
      // 符咒
      case 'charm':
        if (empty($img)) {
            throw new Exception("存檔失敗：符咒遊戲必須傳送 img 參數");
        }
        if (empty($mid)) {
            throw new Exception("存檔失敗：缺少 member_ID");
        }
        $sql = "UPDATE formosoul.charmgame SET 
                charmgame_img1 = :img
                WHERE member_ID = :mid"; 
      break;
      
      //  未傳參數
      default:  throw new Exception("未知的遊戲類型: $gameType");
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
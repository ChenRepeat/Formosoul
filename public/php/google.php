<?php
require_once 'conn.php';
$member = json_decode(file_get_contents("php://input"), true);
session_start();

require_once 'vendor/autoload.php';

$googleToken = $member['google_token'];

// 驗證
$client = new Google_Client(['client_id'=>'561502890556-apker4f72nuiip88t3in35lcpklgaitg.apps.googleusercontent.com']);
$payload = $client->verifyIdToken($googleToken); // 成功傳值 失敗傳false

if($payload){
  // echo $payload['email'].'成功讀取';
  $sqlExamine = "SELECT * FROM member 
                  WHERE email = :email";
  $statement = $pdo->prepare($sqlExamine);
  $statement->bindValue(':email', $payload['email']);
  $statement->execute();
  $user = $statement->fetch();

  $resData=[];// 儲存回傳到前端的DATA

  if(!$user){ // 新會員 註冊
    // google登入讀不到使用者密碼 所以沒有存密碼 
    // 先讀google 的名字進去
    $sql = " 
    START TRANSACTION;
      INSERT INTO formosoul.member(email, name, status, role, createdate, updatetime)
      VALUES (:email, :name, 1, 0, NOW(), NOW());
      SET @USER_ID = LAST_INSERT_ID();
      INSERT INTO formosoul.pointscard (member_ID, mot, shrimp, dice, ring, bue, member_wandcore)
      VALUES (@USER_ID,0,0,0,0,0,0);
      SET @CARD_ID = LAST_INSERT_ID();
      INSERT INTO formosoul.buegame (buegame_count, pointscard_ID, buegame_pass)
        VALUES (0,@CARD_ID,0);
        INSERT INTO formosoul.charmgame (member_ID, charmgame_img1, charmgame_count)
        VALUES (@USER_ID,0,0);
        INSERT INTO formosoul.dicegame (pointscard_ID, dicegame_count, dicegame_pass)
        VALUES (@CARD_ID,0,0);
        INSERT INTO formosoul.motorcyclegame (pointscard_ID, motorcyclegame_count, motorcyclegame_score, motorcyclegame_pass)
        VALUES (@CARD_ID,0,0,0);
        INSERT INTO formosoul.ringgame (pointscard_ID, ringgame_count, ringgame_score, ringgame_pass)
        VALUES (@CARD_ID,0,0,0);
        INSERT INTO formosoul.shrimpgame (pointscard_ID, shrimpgame_count, shrimpgame_score, shrimpgame_pass)
        VALUES (@CARD_ID,0,0,0);
        COMMIT; 
    ";
    $stmt= $pdo->prepare($sql);
    $stmt->bindValue(':email', $payload['email']);
    $stmt->bindValue(':name', $payload['name']);
    $stmt->execute();

    $sqlExamine = "SELECT * FROM member 
                  -- LEFT JOIN member m AND pointscard p 
                  WHERE email = :email";
    $statement = $pdo->prepare($sqlExamine);
    $statement->bindValue(':email', $payload['email']);
    $statement->execute();
    $user = $statement->fetch();
        
    $memberId = $pdo->lastInsertId();
        
  }
  
  // 新會員& 舊會員 登入
  $isFirstLogin = ($user['createdate'] === $user['updatetime']);
  $resData['token']= $googleToken;
  $resData['success']= true;
  $resData['user']=[
    'name'=>$user['name'],
    'wand_core'=>$user[''],
    'message'=>$isFirstLogin? '初次登入':'歡迎再次登入',
  ];

}else{
  $resData['success']=false;
  $resData['message']='Invalid token.';
}

echo json_encode($resData);
?>
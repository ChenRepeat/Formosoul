<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require_once 'conn.php';
  // require 'saveGameData.php';
  // echo $input;

  $member = json_decode(file_get_contents("php://input"), true);
  session_start();

  require_once 'vendor/autoload.php';

  $resData=[];// 儲存回傳到前端的DATA

  $googleToken = $member['google_token'];
  if (!$googleToken) {
    header('Content-Type: application/json');
    $resData["success"] = false;
    $resData["message"] = "Google Token missing";
    
    echo json_encode($resData);
    exit; // 停止執行
}

  $clientId = getenv('VITE_GOOGLE_CLIENT_ID');


  // 驗證
  $client = new Google_Client(['client_id' => $clientId]);
  $payload = $client->verifyIdToken($googleToken); // 成功傳值 失敗傳false

  if($payload){
    // echo $payload['email'].'成功讀取';
    $sqlExamine = "SELECT 
              m.email, 
              m.name, 
              m.createdate, 
              m.updatetime, 
              m.member_ID,
              m.wandcore_ID,
              p.pointscard_ID
              from member m
              left join pointscard p on p.member_ID = m.member_ID
              where email = :email";
    $statement = $pdo->prepare($sqlExamine);
    $statement->bindValue(':email', $payload['email']);
    $statement->execute();
    $user = $statement->fetch();


    
    
    if(!$user){ // 新會員 註冊
      // google登入讀不到使用者密碼 所以沒有存密碼 
      // 先讀google 的名字進去

      // 要把遊戲的0 改成抓傳入的遊戲紀錄值
      $sql = " 
      START TRANSACTION;
        INSERT INTO member(email, name, wandcore_ID, status, role, createdate, updatetime)
        VALUES (:email, :name ,:wandcore_ID, 1, 0, NOW(), NOW());
                  SET @USER_ID = LAST_INSERT_ID();
        INSERT INTO pointscard (member_ID, mot, shrimp, dice, ring, bue, member_wandcore)
        VALUES (@USER_ID,0,0,0,0,0,0);
        SET @CARD_ID = LAST_INSERT_ID();
        INSERT INTO buegame (buegame_count, pointscard_ID, buegame_pass)
        VALUES (0,@CARD_ID,0);
        INSERT INTO charmgame (member_ID, charmgame_img1, charmgame_count)
        VALUES (@USER_ID,0,0);
        INSERT INTO dicegame (pointscard_ID, dicegame_count, dicegame_pass)
        VALUES (@CARD_ID,0,0);
        INSERT INTO motorcyclegame (pointscard_ID, motorcyclegame_count, motorcyclegame_score, motorcyclegame_pass)
        VALUES (@CARD_ID,0,0,0);
        INSERT INTO ringgame (pointscard_ID, ringgame_count, ringgame_score, ringgame_pass)
        VALUES (@CARD_ID,0,0,0);
        INSERT INTO shrimpgame (pointscard_ID, shrimpgame_count, shrimpgame_score, shrimpgame_pass)
        VALUES (@CARD_ID,0,0,0);
        COMMIT; 
        ";
      $stmt= $pdo->prepare($sql);
      $stmt->bindValue(':email', $payload['email']);
      $stmt->bindValue(':name', $payload['name']);
      $stmt->execute();

      $sqlExamine = "SELECT 
              m.email, 
              m.name, 
              m.createdate, 
              m.updatetime, 
              m.member_ID,
              m.wandcore_ID,
              p.pointscard_ID
              from member m
              left join pointscard p on p.member_ID = m.member_ID
              where email = :email";


        $statement = $pdo->prepare($sqlExamine);
        $statement->bindValue(':email', $payload['email']);
        $statement->execute();
        $user = $statement->fetch();

      // $memberId = $pdo->lastInsertId();
      
    }
    // 新會員& 舊會員 登入--------------------------------------
    $isFirstLogin = ($user['createdate'] === $user['updatetime']);
    $resData['token']= $googleToken;
    $resData['success']= true;
    $resData['user']=[
      'name'=>$user['name'], 
      'member_ID'=> $user['member_ID'], 
      'pointscard_ID' => $user['pointscard_ID'],
      'wandcore_ID' => $user['wandcore_ID'],
      'isFirstLogin' => $isFirstLogin,
      'message' => $isFirstLogin ? '第一次登入' : '登入成功',
    ];



    $_SESSION['member_ID'] = $user['member_ID'];
    $_SESSION['name']      = $user['name'];
    $_SESSION['email']     = $user['email'];
    $_SESSION['role']      = 0;
    $_SESSION['pointscard_ID'] = $user['pointscard_ID'];
  

    setcookie("token", $googleToken, [ 
      'expires' => time() + 600,
      'path' => '/',
      // 哪些網域也能使用這個cookie, 之後加入偉育的網址
      'domain' => '',
      // 只能透過 HTTPS 傳送 打包後再看是否改成true
      'secure' => false,
      'httponly' => false,
      // 常用的是 lax 跨站是否帶cookie
      'samesite' => 'Lax',
    ]); // tibame 網站還是有在 cookie 寫 token

    setcookie(
        "user_name", // cookie name
        $user['name'], // cookie value
        // ['expires' =>time() + 600,  // 我把它包成跟上面一樣
        // 'path' => "/"]            
    );

  }
  else{
    $resData['success']= false;
    $resData['message']='Invalid token.'; // != $resData['user']['message']


  }

  echo json_encode($resData);
?>
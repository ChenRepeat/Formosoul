<?php
 session_start();
 require_once 'conn.php';

  $code =  $_GET['code'];
  $state = $_GET['state'];

  $token_url = "https://api.line.me/oauth2/v2.1/token";

  $data = [
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => 'http://localhost/Formosoul/public/php/lineLogin.php', // 網頁的接收網址
    'client_id' => '2008793662',       // Channel ID
    'client_secret' => '37ae333babb2944a79fa3ee3a6e9afdd' // 密碼
];

//用 cURL 發送 POST 請求
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $token_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
// echo "<pre>";
// print_r($result);
// echo "</pre>";

//檢查有沒有成功拿到 Access Token
if (isset($result['access_token'])) {
    $access_token = $result['access_token'];

    //拿 Token 換個資
    $profile_url = "https://api.line.me/v2/profile";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $profile_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // 重點：把 Token 放在信封標頭 (Header) 裡
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $access_token
    ]);
    
    $profile_response = curl_exec($ch);
    curl_close($ch);
    
    $profile = json_decode($profile_response, true);
    
    // echo "<h2>使用者資料：</h2>";
    // echo "<pre>";
    // print_r($profile); //userId, displayName, pictureUrl
    // echo "</pre>";

}

//解碼 id_token 拿出 Email
    if (isset($result['id_token'])) {
        $id_token = $result['id_token'];
        // JWT Token 是由三段組成的，中間那段(payload)藏著資料
        $payload = explode('.', $id_token)[1]; 
        // 因為它是用 Base64 編碼的，我們要解開它
        $data = json_decode(base64_decode($payload), true);

        // echo "您的 Email 是：" . $data['email'];
    }


    $user_email = $data['email'];
    $user_name = $profile['displayName'];
    $user_avatar = $profile['pictureUrl'];

    $stmt = $pdo->prepare('       
            select 
                m.email, 
                m.password, 
                m.name, 
                m.createdate, 
                m.updatetime, 
                m.member_ID,
                p.pointscard_ID
                from formosoul.member m
                left join formosoul.pointscard p on p.member_ID = m.member_ID 
            WHERE email = ?
    ');
    $stmt->execute([$user_email]);
    $member = $stmt->fetch();

//         // 【請暫時加入這段偵錯代碼】
// echo "<h1>偵錯模式</h1>";
// echo "Email: "; var_dump($user_email); // 檢查是不是 NULL
// echo "<br>Name: "; var_dump($user_name);
// echo "<br><pre>"; print_r($result); echo "</pre>"; // 檢查 LINE 到底回傳了什麼
// exit; // 強制停止，不讓它往下跑資料庫

if ($member) {
    // ===【情況 A：是舊會員】===
    // 既然不存 line_id，這裡甚至不需要 UPDATE 資料，直接讓他登入就好
    
    // 如果你想順便更新他的大頭貼，可以加這行 (選擇性)
    // $updateStmt = $pdo->prepare("UPDATE member SET headshot = ? WHERE email = ?");
    // $updateStmt->execute([$user_avatar, $user_email]);
      $_SESSION['member_ID'] = $member['member_ID']; // 注意資料庫欄位大小寫
      $_SESSION['name'] = $member['name'];
      $_SESSION['email'] = $member['email'];
      $_SESSION['role'] = $member['role'];
      $_SESSION['pointscard_ID'] = $member['pointscard_ID'];
} else {
    // ===【情況 B：是新會員】===
    // 建立新資料時，就只存 Email 和基本資料
    $insertStmt = $pdo->prepare('
    START TRANSACTION;
        INSERT INTO formosoul.member(email, password, status, role, pointscard, createdate, updatetime)
        VALUES (:email, :password , 1, 0, 0, NOW(), NOW());
        SET @USER_ID = LAST_INSERT_ID();
        INSERT INTO formosoul.pointscard (member_ID,count,mot,shrimp,dice,ring,bue,member_wandcore)
            VALUES (@USER_ID,0,0,0,0,0,0,0);
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
    ');
    $insertStmt->execute([$user_email, $user_name, $user_avatar]);
    
    $newUserId = $pdo->lastInsertId();

    // 2. 【關鍵】寫入 Session (發通行證)
    // 確保檔案最上方有寫 session_start();
    $_SESSION['member_ID'] = $newUserId; // 存 ID (最重要)
    $_SESSION['name'] = $user_name;      // 存名字 (方便顯示 "你好, XXX")
    $_SESSION['email'] = $user_email;    // 存 Email (如果有需要)
    $_SESSION['role'] = 0;               // 存權限 (方便前端判斷顯示內容)
        
}
// 1. 準備要傳給前端的資料
$loginData = [
    'member_ID' => $_SESSION['member_ID'],
    'name'      => $_SESSION['name'],
    'role'      => $_SESSION['role'],
    'pointscard_ID'      => $_SESSION['pointscard_ID'],
];

// 2. 編碼資料 (JSON -> Base64)
$dataToken = base64_encode(json_encode($loginData));

// 3. 【關鍵修改】
//   A. 改成導向首頁 (避免直接去會員頁被擋)
//   B. 加上 ?loginData=... (把資料帶過去)
header("Location: http://localhost:5173/tjd103/?loginData=" . $dataToken);
exit;
?>
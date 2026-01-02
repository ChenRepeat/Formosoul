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

}

//解碼 id_token 拿出 Email
    if (isset($result['id_token'])) {
        $id_token = $result['id_token'];
        // JWT Token 是由三段組成的，中間那段(payload)藏著資料
        $payload = explode('.', $id_token)[1]; 
        // 因為它是用 Base64 編碼的，我們要解開它
        $data = json_decode(base64_decode($payload), true);
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
                from member m
                left join pointscard p on p.member_ID = m.member_ID 
            WHERE email = ?
    ');
    $stmt->execute([$user_email]);
    $member = $stmt->fetch();

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

    // 因為是用 LINE 登入，資料庫的 password 欄位給一個隨機亂碼即可
    $randomPassword = bin2hex(random_bytes(6));

    try {
        // 1. 啟動交易 (確保下面動作要嘛全成功，要嘛全失敗)
        $pdo->beginTransaction();

        // 2. 新增會員 (Member)
        // 注意：我幫你補上了 name 欄位，不然你的 $user_name 沒地方存
        $sql_member = "INSERT INTO member (email, password, name, status, role, createdate, updatetime) 
                       VALUES (?, ?, ?, 1, 0, NOW(), NOW())";
        
        $stmt = $pdo->prepare($sql_member);
        // 這裡順序要對應上面的問號：email, password, name
        $stmt->execute([$user_email, $randomPassword, $user_name]);
        
        // ★ 拿到剛產生的 會員ID
        $newUserId = $pdo->lastInsertId();

        // 3. 新增集點卡 (PointsCard)
        $sql_card = "INSERT INTO pointscard (member_ID, mot, shrimp, dice, ring, bue, member_wandcore) 
                     VALUES (?, 0, 0, 0, 0, 0, 0)";
        
        $stmt = $pdo->prepare($sql_card);
        $stmt->execute([$newUserId]); // 傳入剛剛拿到的會員ID
        
        // ★ 拿到剛產生的 集點卡ID
        $newCardId = $pdo->lastInsertId();

        // 4. 初始化所有遊戲 (用剛剛拿到的 ID)
        // Charmgame 用 user_ID
        $pdo->prepare("INSERT INTO charmgame (member_ID, charmgame_img1, charmgame_count) VALUES (?, 0, 0)")
            ->execute([$newUserId]);

        // 其他遊戲用 card_ID
        $pdo->prepare("INSERT INTO buegame (pointscard_ID, buegame_count, buegame_pass) VALUES (?, 0, 0)")
            ->execute([$newCardId]);
            
        $pdo->prepare("INSERT INTO dicegame (pointscard_ID, dicegame_count, dicegame_pass) VALUES (?, 0, 0)")
            ->execute([$newCardId]);
            
        $pdo->prepare("INSERT INTO motorcyclegame (pointscard_ID, motorcyclegame_count, motorcyclegame_score, motorcyclegame_pass) VALUES (?, 0, 0, 0)")
            ->execute([$newCardId]);
            
        $pdo->prepare("INSERT INTO ringgame (pointscard_ID, ringgame_count, ringgame_score, ringgame_pass) VALUES (?, 0, 0, 0)")
            ->execute([$newCardId]);
            
        $pdo->prepare("INSERT INTO shrimpgame (pointscard_ID, shrimpgame_count, shrimpgame_score, shrimpgame_pass) VALUES (?, 0, 0, 0)")
            ->execute([$newCardId]);

        // 5. 全部成功，提交確認！
        $pdo->commit();

        // 6. 設定 Session (讓系統知道他登入了)
        $_SESSION['member_ID'] = $newUserId;
        $_SESSION['name']      = $user_name;
        $_SESSION['email']     = $user_email;
        $_SESSION['role']      = 0;
        $_SESSION['pointscard_ID'] = $newCardId;

    } catch (Exception $e) {
        // 如果中間出錯，全部取消 (Rollback)
        $pdo->rollBack();
        // 為了除錯，先印出錯誤訊息 (上線後可拿掉)
        die("註冊失敗，錯誤代碼：" . $e->getMessage());
    }
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
//   B. 加上 ?loginData=... (把資料帶過去)
header("Location: http://localhost:5173/tjd103/?loginData=" . $dataToken);
exit;
?>
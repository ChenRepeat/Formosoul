<?php
session_start();
require 'conn.php';
// $member_wand = json_decode(file_get_contents("php://input"), true);

$code = $_GET['code'];
$state = $_GET['state'];

$host = $_SERVER['HTTP_HOST'];
$redirect_uri = '';

$token_url = "https://api.line.me/oauth2/v2.1/token";

// 判斷是否為本機環境
if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
    // 【本機環境】
    $redirect_uri = 'http://localhost/Formosoul/public/php/lineLogin.php';
} else {
    // 【線上環境】
    $redirect_uri = 'https://tibamef2e.com/tjd103/php/lineLogin.php'; 
}

$data = [
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri,
    'client_id' => '2008793662',
    'client_secret' => '37ae333babb2944a79fa3ee3a6e9afdd'
];

// 1. 拿 Access Token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $token_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if (!isset($result['access_token'])) {
    die("Line Login Error: Access Token not found.");
}

$access_token = $result['access_token'];

// 2. 拿 Profile (包含 line_id, displayName, pictureUrl)
$profile_url = "https://api.line.me/v2/profile";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $profile_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $access_token
]);

$profile_response = curl_exec($ch);
curl_close($ch);

$profile = json_decode($profile_response, true);

// 3. 解析 Email (從 id_token)
$user_email = null;
if (isset($result['id_token'])) {
    $id_token = $result['id_token'];
    $payload = explode('.', $id_token)[1];
    $data = json_decode(base64_decode($payload), true);
    $user_email = $data['email'] ?? null;
}

// 取得 Line 的使用者資訊
$line_user_id = $profile['userId']; // ★ 這是 Line 唯一的 User ID
$user_name = $profile['displayName'];
$user_avatar = $profile['pictureUrl'];

// ★★★ 核心邏輯修改開始 ★★★
//先檢查 line_id 是否已存在
$stmt = $pdo->prepare('
    SELECT m.*, p.pointscard_ID 
    FROM member m
    LEFT JOIN pointscard p ON p.member_ID = m.member_ID 
    WHERE m.line_id = ?
');
$stmt->execute([$line_user_id]);
$member = $stmt->fetch();

if ($member) {
    // ===【情況 A：完全是舊會員 (Line ID 已存在)】===
    // 直接登入
} else {
    // Line ID 不存在，嘗試用 Email 找人
    $stmt = $pdo->prepare('
        SELECT m.*, p.pointscard_ID 
        FROM member m
        LEFT JOIN pointscard p ON p.member_ID = m.member_ID 
        WHERE m.email = ?
    ');
    $stmt->execute([$user_email]);
    $member = $stmt->fetch();

    if ($member) {
        // ===【情況 B：信箱已存在，但沒綁 Line (Google/Email 註冊過)】===
        // 動作：把 Line ID 綁定到這個舊帳號上
        $updateStmt = $pdo->prepare("UPDATE member SET line_id = ? WHERE member_ID = ?");
        $updateStmt->execute([$line_user_id, $member['member_ID']]);
        
        // (選擇性) 如果你想更新他的大頭貼或名稱，也可以在這裡做 UPDATE
    } else {
        // ===【情況 C：完全新會員】===
        // 動作：註冊新帳號 (同時存 line_id 和 email)

        try {
            $pdo->beginTransaction();
            // $wandcore_ID = $member_wand['wandcore_ID'] ?? null;
            // 1. 新增會員 (記得把 line_id 寫進去)
            $sql_member = "INSERT INTO member (email, password, name, line_id, wandcore_ID, status, role, createdate, updatetime) 
                           VALUES (?, NULL, ?, ?, :wandcore_ID, 1, 0, NOW(), NOW())";
            
            $stmt = $pdo->prepare($sql_member);
            $stmt->execute([$user_email, $user_name, $line_user_id]);
            
            $newUserId = $pdo->lastInsertId();

            // 2. 新增集點卡
            $sql_card = "INSERT INTO pointscard (member_ID, mot, shrimp, dice, ring, bue, member_wandcore) 
                         VALUES (?, 0, 0, 0, 0, 0, 0)";
            
            $stmt = $pdo->prepare($sql_card);
            $stmt->execute([$newUserId]);
            $newCardId = $pdo->lastInsertId();

            // 3. 初始化遊戲 (複製你原本的代碼)
            $pdo->prepare("INSERT INTO charmgame (member_ID, charmgame_img1, charmgame_count) VALUES (?, 0, 0)")
                ->execute([$newUserId]);
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

            $pdo->commit();

            // 為了讓下面程式碼共用 $member 變數，這裡手動組一個 member 陣列
            $member = [
                'member_ID' => $newUserId,
                'name' => $user_name,
                'email' => $user_email,
                'role' => 0,
                'pointscard_ID' => $newCardId,
                'wandcore_ID' => null // 新會員預設可能沒有魔杖
            ];

        } catch (Exception $e) {
            $pdo->rollBack();
            die("註冊失敗：" . $e->getMessage());
        }
    }
}

// ★★★ 共用的登入 Session 設定 (不管是 A, B 還是 C) ★★★

$_SESSION['member_ID'] = $member['member_ID'];
$_SESSION['name']      = $member['name'];
$_SESSION['email']     = $member['email'];
$_SESSION['role']      = $member['role'];
$_SESSION['pointscard_ID'] = $member['pointscard_ID'];
$_SESSION['wandcore_ID'] = $member['wandcore_ID'] ?? null;

// 準備傳回前端的資料
$loginData = [
    'member_ID' => $_SESSION['member_ID'],
    'name'      => $_SESSION['name'],
    'role'      => $_SESSION['role'],
    'pointscard_ID' => $_SESSION['pointscard_ID'],
    'wandcore_ID' => $_SESSION['wandcore_ID'] ?? null,
];

$dataToken = base64_encode(json_encode($loginData));
// 設定前端網址
if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
    // 【本機開發環境】 (Vite 預設 port)
    $frontend_url = "http://localhost:5173/tjd103";
} else {
    // 【線上正式環境】 (您的正式網域)
    $frontend_url = "https://tibamef2e.com/tjd103";
}

// 跳轉回前端
header("Location: " . $frontend_url . "/?loginData=" . $dataToken);
exit;
?>
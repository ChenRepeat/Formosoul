<?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);


  $sql = '
    select email, password, name
    from member
    where email = :email and password = :pwd
    ';

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':email', $member['email']);
    $stmt->bindValue(':pwd', $member['password']);

    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $resbody = [];

        if($user){
            $resbody['success'] = true;
            // 隨機 64 字元的字串
            $token = bin2hex(random_bytes(32));
            $resbody['token'] = $token;
            $resbody['user'] = [
                // 之後會有要回傳給前端的資料 讓前端能去接
                // 'email' => $user['email']
                'name' => $user['name']
            ];

            setcookie("token", $token, [
                'expires' => time() + 600,
                'path' => '/',
                // 哪些網域也能使用這個cookie, 之後加入偉育的網址
                'domain' => '',
                // 只能透過 HTTPS 傳送 打包後再看是否改成true
                'secure' => false,
                'httponly' => false,
                // 常用的是 lax 跨站是否帶cookie
                'samesite' => 'Lax',
            ]);
            setcookie("user_name", $user['name'], time() + 600, "/");
        }else{
            $resbody['success'] = false;
            $resbody['message'] = '帳號或密碼錯誤，請重新輸入。';
        }
        
        // 告訴瀏覽器是純資料 (JSON)
        header('Content-Type: application/json; charset=utf-8');
        // 把 PHP 陣列 ($rows) 轉成 JSON 字串並傳到前端
        echo json_encode($resbody);
?>
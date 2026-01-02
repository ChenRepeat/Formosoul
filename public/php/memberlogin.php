<?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  
  
  
  $sql = '
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
        where email = :email and password = :pwd;
    ';

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':email', $member['email']);
    $stmt->bindValue(':pwd', $member['password']);

    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $resbody = [];
    
    if($user){
            $isFirstLogin = ($user['createdate'] === $user['updatetime']);
            $resbody['success'] = true;
            // 隨機 64 字元的字串
            $token = bin2hex(random_bytes(32));
            $resbody['token'] = $token;
            $resbody['user'] = [
                // 之後會有要回傳給前端的資料 讓前端能去接
                // 'email' => $user['email']
                'name' => $user['name'],
                'member_ID' => $user['member_ID'],
                'pointscard_ID' => $user['pointscard_ID'],
                'isFirstLogin' => $isFirstLogin,
                'message' => $isFirstLogin ? '第一次登入' : '登入成功',
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
            setcookie(
                "user_name", 
                $user['name'], 
                time() + 600, 
                "/",            
            );
        }else{
            $resbody['success'] = false;
            $resbody['message'] = 'Incorrect username or password, Please enter again。';
        }
        

        // 把 PHP 陣列 ($rows) 轉成 JSON 字串並傳到前端
        echo json_encode($resbody);


        



?>
<?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);


  $sql = '
    select email, password
    from member
    where email = :email and password = :pwd
    ';

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':email', $member['email']);
    $stmt->bindValue(':pwd', $member['password']);

    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // $resbody['email'] = $member['email'];
    // $resbody['password'] = $member['password'];

    $resbody = [];

        if($user){
            $resbody['success'] = true;
            // 隨機 64 字元的字串
            $resbody['token'] = bin2hex(random_bytes(32));
            $resbody['user'] = [
                // 之後會有要回傳給前端的資料
                'email' => $user['email']
            ];
        }else{
            $resbody['success'] = false;
            $resbody['message'] = '帳號或密碼錯誤，請重新輸入。';
        }
        
        // 告訴瀏覽器是純資料 (JSON)
        header('Content-Type: application/json; charset=utf-8');
        // 把 PHP 陣列 ($rows) 轉成 JSON 字串並傳到前端
        echo json_encode($resbody);
?>
<?php
    require_once 'conn.php';
    session_start();
    $member = json_decode(file_get_contents("php://input"), true);
    $sql = '
        SELECT email, line_ID, password	
        FROM member 
        WHERE email = :email;
    ';
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $member['email']);
    $stmt->execute();
    $checkemail = $stmt->fetch();
    if($checkemail && $checkemail['line_ID'] == null && $checkemail['password'] != null){
        $changeotp = mt_rand(100000, 999999);

        $_SESSION['otp'] = $changeotp;
        $_SESSION['otp_email'] = $member['email'];
        $_SESSION['otp_expires'] = time() + 600;

        $encryptedOtp = base64_encode($changeotp);
        echo json_encode([
            'success' => true,
            'message' => '正確',
            'changeotp' => $encryptedOtp
            //  JSON_UNESCAPED_UNICODE 讓回傳的訊息是正常的
        ], JSON_UNESCAPED_UNICODE);
    }else{
        echo json_encode([
            'success' => false,
            'message' => 'Cannot find email or other login',
        ], JSON_UNESCAPED_UNICODE);
        exit; 
    }



 
?>
<?php
    require_once 'conn.php';
    session_start();
    $member = json_decode(file_get_contents("php://input"), true);
    $sql = '
        SELECT email
        FROM member 
        WHERE email = :email;
    ';
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $member['email']);
    $stmt->execute();
    $checkemail = $stmt->fetchColumn();
    if($checkemail){
        $changeotp = mt_rand(100000, 999999);

        $_SESSION['otp'] = $changeotp;
        $_SESSION['otp_email'] = $member['email'];
        $_SESSION['otp_expires'] = time() + 600;

        $encryptedOtp = base64_encode($changeotp);
        echo json_encode([
            'success' => true,
            'message' => '正確',
            'changeotp' => $encryptedOtp
        ], JSON_UNESCAPED_UNICODE);
    }else{
        echo json_encode([
            'success' => false,
            'message' => 'Cannot find email',
        ], JSON_UNESCAPED_UNICODE);   
    }



 
?>
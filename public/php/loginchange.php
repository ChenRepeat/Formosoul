<?php
    require_once 'conn.php';
    $member = json_decode(file_get_contents("php://input"), true);
    session_start();

// 1. 先檢查 Session 是否存在
if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_expires']) || !isset($_SESSION['otp_email'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Session expired or invalid access. Please try again.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. 檢查是否過期 (10分鐘 = 600秒)
if (time() > $_SESSION['otp_expires']) {
    unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['otp_expires']);
    echo json_encode([
        'success' => false,
        'message' => 'The verification code has expired. Please obtain a new one.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 3. 驗證 OTP 是否正確
// 注意：前端傳過來的是 changeotp，這裡比對的是 session 裡的 otp
if ((string)$_SESSION['otp'] !== (string)$member['changeotp']) {
    echo json_encode([
        'success' => false,
        'message' => 'Verification code error'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

    $sql = '
        UPDATE member
        SET password = :password
        WHERE email = :email;
    ';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':password', $member['Newpassword']);
    $stmt->bindValue(':email', $_SESSION['otp_email']);
    $change = $stmt->execute();

    if($change){
        echo json_encode([
            'success' => true,
            'message' => 'Password updated successfully',
        ]);
    }else{
        echo json_encode([
            'success' => false,
            'message' => 'Password updated failed.'
            ]);
        exit;
    }
?>
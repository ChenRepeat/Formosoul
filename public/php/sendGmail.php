<?php
// --- 引入 PHPMailer 核心檔案 ---
require __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * 發送開通通知信的函式
 * 自動判斷環境：本機用 Gmail，線上用 Localhost
 */
function sendPasswordEmail($userEmail, $userName, $plainPassword) {
    
    $mail = new PHPMailer(true);

    try {
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP(); 

        // ★★★ 關鍵邏輯：判斷現在是在本機還是在伺服器 ★★★
        $host = $_SERVER['HTTP_HOST']; // 抓目前的網域

        if ($host === 'localhost' || $host === '127.0.0.1') {
            // ==========================================
            //  環境 A：本機開發 (使用 Gmail)
            // ==========================================
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'michael4292015@gmail.com'; // 你的 Gmail
            $mail->Password   = 'fgso eohd iids dnuo';      // 你的應用程式密碼
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            
            // 本機 SSL 憑證修正
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            // 本機寄件人 (顯示你的 Gmail)
            $mail->setFrom('michael4292015@gmail.com', 'Michael 系統管理員');

        } else {
            // ==========================================
            //  環境 B：線上伺服器 (使用內部通道，繞過防火牆)
            // ==========================================
            $mail->Host       = 'localhost';
            $mail->Port       = 25;
            $mail->SMTPAuth   = false;
            $mail->SMTPAutoTLS = false;
            $mail->SMTPSecure = false;

            // 線上寄件人 (建議用 no-reply 避免被擋，或是維持你的 Gmail 也可以試試)
            // 這裡用 no-reply@網域 比較標準，但顯示名稱還是可以是你的名字
            $mail->setFrom('no-reply@tibamef2e.com', 'Michael 系統管理員');
        }

        // --- 收件人與內容設定 (通用) ---
        $mail->addAddress($userEmail, $userName);

        $mail->isHTML(true);                                  
        $mail->Subject = '【系統通知】您的帳號已開通，內含登入密碼';    
        $mail->Body = "
            <h3>您好，{$userName}：</h3>
            <p>歡迎加入！您的帳號已經建立成功。</p>
            <p>這是您的初始登入密碼：</p>
            <div style='background-color: #f2f2f2; padding: 10px; border-left: 4px solid #007bff; font-family: monospace; font-size: 18px;'>
                {$plainPassword}
            </div>
            <p>請盡快登入並修改密碼。</p>
        ";
        
        $mail->AltBody = "您好 {$userName}，您的初始密碼是：{$plainPassword}，請盡快登入修改。";

        // --- 發送 ---
        $mail->send();
        return true;

    } catch (Exception $e) {
        // 失敗回傳 false，讓前端顯示「新增成功但寄信失敗」
        // 如果需要除錯，可以取消下面註解
        // echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}
?>
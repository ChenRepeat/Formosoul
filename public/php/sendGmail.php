<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * 發送開通通知信的函式
 * * @param string $userEmail 收件人信箱
 * @param string $userName  收件人名稱
 * @param string $plainPassword 給使用者的明碼密碼
 * @return bool 發送成功回傳 true，失敗回傳 false
 */
function sendPasswordEmail($userEmail, $userName, $plainPassword) {
    
    // 建立 PHPMailer 實體
    $mail = new PHPMailer(true);

    try {
        // --- 伺服器設定 (Server Settings) ---
        $mail->CharSet    = 'UTF-8';
        $mail->isSMTP();                                        
        $mail->Host       = 'smtp.gmail.com';                   
        $mail->SMTPAuth   = true;                               
        $mail->Username   = 'michael4292015@gmail.com';         // Gmail
        $mail->Password   = 'fgso eohd iids dnuo';              // 應用程式密碼
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     
        $mail->Port       = 587;                                

        // --- 收件人設定 (使用傳進來的變數) ---
        $mail->setFrom('michael4292015@gmail.com', 'Michael 系統管理員'); // 寄件人
        $mail->addAddress($userEmail, $userName);              // 收件人

        // --- 郵件內容 ---
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
        return true; // 成功回傳 true

    } catch (Exception $e) {
        // 失敗回傳 false
        return false;
    }
}
?>
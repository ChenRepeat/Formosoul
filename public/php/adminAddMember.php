<?php
require 'sendGmail.php'; 
require 'conn.php'; 

//產生 8 位數亂數密碼
// function generateRandomPassword($length = 8) {
//     // 密碼字元庫 (去除了容易混淆的字元，如 1, l, I, 0, O)
//     $chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    
//     // 亂數字串，然後取8次的一個字拼接
//     $password = '';
//     for ($i = 0; $i < $length; $i++) {
//         $password .= $chars[rand(0, strlen($chars) - 1)];
//     }
    
//     return $password;
// }

// 收件人資料
$email = 'michael4292018@gmail.com';
$name  = '收件測試人';
$pwd = generateRandomPassword(8);

try {
    $sql = "INSERT INTO member (email, `name`, `password`, createdate, `status`, updatetime, `role`) VALUES (?, ?, ?, NOW(), 1, NOW(), 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $name, $pwd]); 

    //取得新建立的ID
    echo "使用者建立成功！(ID: " . $pdo->lastInsertId() . ")<br>";

    // 寄信
    echo "📧 正在寄信...<br>";
    if (sendPasswordEmail($email, $name, $pwd)) {
        echo "<h2 style='color:green'>帳號已建立且信件已寄出。</h2>";
    } else {
        echo "<h2 style='color:red'>帳號建好了，但信寄失敗了。</h2>";
    }

} catch (PDOException $e) {
    echo "<h2 style='color:red'>資料庫錯誤: " . $e->getMessage() . "</h2>";
}
?>
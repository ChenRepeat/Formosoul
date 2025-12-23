<?php

require_once 'db_config.php';

// ---------------------------------------------------------
// 設定 CORS 與 標頭
// ---------------------------------------------------------

// 允許來自任何網域的請求 (開發階段方便，正式上線建議改成前端網址 'http://localhost:5173')
header("Access-Control-Allow-Origin: *"); 

// 允許的請求方法 (GET, POST, OPTIONS, PUT, DELETE)
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 

// 允許的 Header 內容
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

// 設定回傳內容為 JSON 格式
header('Content-Type: application/json; charset=utf-8');

// 處理 "預檢請求" (Preflight Request)
// 當 Vue 發送複雜請求時，瀏覽器會先送一個 OPTIONS 請求，這裡直接回傳 OK 結束，不進入資料庫連線
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

//建立資料庫連線物件
$dsn = "mysql:host=".$db_host.";port=".$db_port.";dbname=".$db_select.";charset=utf8";

$options = [
    //有錯誤即顯示
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    //回傳的資料為欄位名稱 不要多餘的參數索引資料
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    //雲端資料庫加密
    // __DIR__ 代表目前這個 conn.php 所在的資料夾
    PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/isrgrootx1.pem',
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
];

// 4. 建立連線
$pdo = new PDO($dsn, $db_user, $db_pass, $options);

?>
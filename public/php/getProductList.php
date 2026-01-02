<?php
/* 測試連線
echo "這裡有被執行到！";
exit; // 讓程式停在這裡，不執行後面的
*/


// 連線 ----------
require_once 'conn.php';

// SQL ----------
$sql = "SELECT * 
        FROM product p
	    LEFT JOIN product_detail pd
        on p.product_ID = pd.product_ID;";



// 執行並查詢，會回傳查詢結果的物件，必須使用fetch、fetchAll...等方式取得資料 ----------
$database = $pdo->query($sql);

// 抓出全部且依照順序封裝成一個二維陣列 ----------
// (PDO::FETCH_ASSOC) 代表只要欄位名稱的陣列，不用數字索引的陣列，如果沒加，兩種都會封裝進陣列，變成兩倍資訊
$productdata = $database->fetchAll(PDO::FETCH_ASSOC);


// step1 分割圖片的資料 ----------
// 寫法一
foreach($productdata as &$product){    // 因為要直接修改原資料，所以加 ＆ 建立連結

/* 這樣寫可能會有問題，因為資料庫的 image 欄位如果誤刪，沒有 key, 程式會報錯
if($product["image"]){
    $product["image"] = explode('|', $product["image"]);
}else{
    $product["image"] = [];   
}
*/
/* 使用 !empty 方式，因為當 key undefined 時，還是會回傳 true
if(!empty($product["image"])){
    $product["image"] = explode('|', $product["image"]);
}else{
    $product["image"] = []; 
}
*/

// 改成三元運算子的方式
$product["image"] = !empty($product["image"]) ? explode('|', $product["image"]): [];


// step2 新增 isLike 欄位 ----------
$product["isLike"] = false;


// step3 把庫存跟價格從字串轉成數字 ----------
$product['price'] = (int)$product['price'];
$product['stock'] = (int)$product['stock'];

}
// 銷毀參考變數（斷開連結），避免之後要加其他程式碼時誤用
unset($product); 


// 6. 輸出
// JSON_UNESCAPED_UNICODE 代表
echo json_encode($productdata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); 



/* 寫法二 透過 index 修改
foreach ($productdata as $index => $product) {

    //$product 是「複製品」，所以要用 $productdata[$index] 改

    // 處理圖片
    if (!empty($product['images'])) {
        // 算出陣列結果，塞回原本的陣列
        $productdata[$index]['images'] = explode('|', $product['images']);
    } else {
        $productdata[$index]['images'] = [];
    }

    // 處理數字 (塞回原本的陣列)
    $productdata[$index]['price'] = (int)$product['price'];
    $productdata[$index]['stock'] = (int)$product['stock'];

    // 新增欄位 (塞回原本的陣列)
    $productdata[$index]['isLike'] = false;
}

// 6. 輸出
// 因為都是用 $productdata[$index] = ... 修改的，所以這裡印出來就是改好的
echo json_encode($productdata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
*/


?>
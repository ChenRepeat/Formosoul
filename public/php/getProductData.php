<?php
require_once 'conn.php';

$result = [];

if (isset($_GET['id'])) {
    // 查詢商品主資料+主圖
    $id = $_GET['id'];

    $sql = "
      SELECT * FROM product WHERE product_ID = ?
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET['id']]);
    //PDO::FETCH_ASSOC=>過濾掉索引陣列
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
     //如果 image 欄位是 NULL，就給空字串，避免程式報錯
      $imageString = $product['image'] ?? ''; 
      
      if (!empty($imageString)) {
        //切割字串：把 "img1.jpg|img2.jpg|img3.jpg" 炸開變成陣列
          $images = explode('|', $imageString);
          //取出主圖：陣列第 0 個位置就是主圖
          $product['main_image'] = $images[0];
          // 取出副圖：意思是「從第 1 個位置開始切到最後」
          // 剛好把第 0 個的主圖排除掉了
          $product['sub_images'] = array_slice($images, 1);
      } else {
          $product['main_image'] = null;
          $product['sub_images'] = [];
      }

      $sql_detail = "SELECT * FROM product_detail WHERE product_ID = ?";
      $stmt_detail = $pdo->prepare($sql_detail);
      $stmt_detail->execute([$id]);

      $details = $stmt_detail->fetchAll(PDO::FETCH_ASSOC);

      // 將詳細資料掛載到 product 物件下的一個新屬性，'details'
      $product['details'] = $details;

      $result = $product;
  } else {
      $result = ['error' => '查無此商品'];
  }

} else {
    //查詢全部商品列表
    $sql = "
      SELECT *, 
      SUBSTRING_INDEX(image, '|', 1) AS main_image 
      FROM product 
      ORDER BY product_ID DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// 統一輸出JSON
echo json_encode($result);
?>
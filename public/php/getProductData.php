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
      // --- 處理圖片 (原本的邏輯) ---
      $imageString = $product['image'] ?? ''; 
      
      if (!empty($imageString)) {
          $images = explode('|', $imageString);
          $product['main_image'] = $images[0];
          $product['sub_images'] = array_slice($images, 1);
      } else {
          $product['main_image'] = null;
          $product['sub_images'] = [];
      }

      // ==========================================
      // STEP 2: 查詢商品詳細資料 (Product Detail)
      // ==========================================
      // 假設兩張表是用 product_ID 關聯
      $sql_detail = "SELECT * FROM product_detail WHERE product_ID = ?";
      $stmt_detail = $pdo->prepare($sql_detail);
      $stmt_detail->execute([$id]);
      
      // 使用 fetchAll 抓取所有相關的詳細資料 (例如: S號, M號, L號)
      // 如果你的詳細資料只有一筆，這會回傳一個只有一項的陣列，前端也好處理
      $details = $stmt_detail->fetchAll(PDO::FETCH_ASSOC);

      // 將詳細資料掛載到 product 物件下的一個新屬性，例如 'specs' 或 'details'
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
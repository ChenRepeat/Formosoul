<?php
require_once 'conn.php';

$result = []; // 最終回傳資料

if (isset($_GET['id'])) {
    // 查詢商品主資料+主圖
    $sql = "
      SELECT p.*, img.url AS main_image
      FROM product p
      LEFT JOIN product_images img 
      ON p.product_ID = img.product_ID 
      AND img.is_main = 1
      WHERE p.product_ID = ?
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET['id']]);
    //PDO::FETCH_ASSOC=>取消撈出來為索引的陣列
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        //找副圖
        $sql_subimg = "
          SELECT * FROM product_images
          WHERE product_ID = ? AND is_main = 0
        ";        

        $stmt_sub = $pdo->prepare($sql_subimg);
        $stmt_sub->execute([$_GET['id']]);
        
        $sub_images = $stmt_sub->fetchAll(PDO::FETCH_ASSOC);

        $product['sub_images'] = $sub_images;

        $result = $product;
    } else {
        $result = ['error' => '查無此商品'];
    }

} else {
    //查詢全部商品列表
    $sql = "
    SELECT p.*, img.url AS main_image
    FROM product p
    LEFT JOIN product_images img 
    ON p.product_ID = img.product_ID 
    AND img.is_main = 1
    ORDER BY p.product_ID DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// 統一輸出JSON
echo json_encode($result);
?>
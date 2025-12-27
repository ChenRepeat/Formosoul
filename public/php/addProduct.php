<?php
 require_once 'conn.php';

// 3. 處理寫入邏輯
try {
  //交易模式：全部成功或全部失敗
  $pdo->beginTransaction();

  //寫入product ?為預處理 接收execute傳的值
  $sqlProduct = "INSERT INTO product (
      name_zh, name_en, `type`, price, stock, createdate, `update`, `status`
  ) VALUES (
      ?, ?, ?, ?, ?, NOW(), NOW(), ?
  )";

  $stmtProduct = $pdo->prepare($sqlProduct);
  $stmtProduct->execute([
      $_POST['nameZh'],
      $_POST['nameEn'],
      $_POST['type'],
      $_POST['price'],
      $_POST['stock'],
      $_POST['status']
  ]);

  //取得新增商品ID
  $newProductID = $pdo->lastInsertId();

  // 寫入product_detail 
  $sqlDetail = "INSERT INTO product_detail (
      product_ID, description_zh, description_en, story_zh, story_en, use_zh, use_en
  ) VALUES (
      ?, ?, ?, ?, ?, ?, ?
  )";

  $stmtDetail = $pdo->prepare($sqlDetail);
  $stmtDetail->execute([
      $newProductID,
      $_POST['descriptionZh'],
      $_POST['descriptionEn'],
      $_POST['storyZh'],
      $_POST['storyEn'],
      $_POST['useZh'],
      $_POST['useEn']
  ]);

  // 寫入product_images 
  $uploadDir = '../uploads/';
  //建立資料夾
  if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
  }

  // SQL 值帶幾個? =>execute就要帶幾個值
  $sqlImg = "INSERT INTO product_images (product_ID, `url`, is_main) VALUES (?,?,?)";
  $stmtImg = $pdo->prepare($sqlImg);

  // 主圖 error錯誤代碼 => 0 代表成功
  if (isset($_FILES['mainImage']) && $_FILES['mainImage']['error'] === UPLOAD_ERR_OK) {
    //抓副檔名(png)
      $ext = pathinfo($_FILES['mainImage']['name'], PATHINFO_EXTENSION);
    //產生亂碼ID拼接避免重複
      $fileName = uniqid('main_') . '.' . $ext;
      //從暫存區搬走圖片到指定資料夾
      if(move_uploaded_file($_FILES['mainImage']['tmp_name'], $uploadDir . $fileName)) {
          // 寫入資料庫
          $stmtImg->execute([
              $newProductID, 
              $fileName,
              1           // is_main
          ]);
      }
  }

  // 小圖
  if (isset($_FILES['subImages'])) {
    //抓數量
      $count = count($_FILES['subImages']['name']);
      
      for ($i = 0; $i < $count; $i++) {
          if ($_FILES['subImages']['error'][$i] === UPLOAD_ERR_OK) {
              $ext = pathinfo($_FILES['subImages']['name'][$i], PATHINFO_EXTENSION);
              $fileName = uniqid('sub_') . '.' . $ext;
              
              if(move_uploaded_file($_FILES['subImages']['tmp_name'][$i], $uploadDir . $fileName)) {
                  // 寫入資料庫
                  $stmtImg->execute([
                      $newProductID, 
                      $fileName, 
                      0           // is_main 為 0
                  ]);
              }
          }
      }
  }

  // 【最後一步】提交交易 (確認存檔)
  $pdo->commit();

  echo json_encode([
      'success' => true, 
      'message' => '新增成功',
      'id' => $newProductID
  ]);

} catch (Exception $e) {
  // 【發生錯誤】復原所有動作 (Rollback)
  if ($pdo->inTransaction()) {
      $pdo->rollBack();
  }
  echo json_encode(['success' => false, 'message' => '處理失敗: ' . $e->getMessage()]);
}
?>
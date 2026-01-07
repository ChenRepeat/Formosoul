<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'conn.php';

$response = ['success' => false, 'message' => ''];

try {
    if (!isset($_POST['product_ID']) || empty($_POST['product_ID'])) {
        throw new Exception('缺少商品 ID');
    }

    $id = $_POST['product_ID'];
    
    // 設定檔案存放路徑
    $uploadDir = '../Shop/'; 

    // 建立上傳資料夾
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // ====================================================
    // 1. 先撈出舊的圖片字串
    // ====================================================
    $stmt_old = $pdo->prepare("SELECT image FROM product WHERE product_ID = ?");
    $stmt_old->execute([$id]);
    $row = $stmt_old->fetch(PDO::FETCH_ASSOC);
    
    $oldImageString = $row['image'] ?? '';
    // 如果資料庫有圖，轉成陣列；如果沒圖，初始化為空陣列
    $currentImages = !empty($oldImageString) ? explode('|', $oldImageString) : [];

    // ====================================================
    // 2. 處理新主圖 (Main Image)
    // ====================================================
    // 如果使用者有上傳新主圖，就 "取代" 陣列的第一個元素 (index 0)
    if (isset($_FILES['mainImage']) && $_FILES['mainImage']['error'] === UPLOAD_ERR_OK) {
        
        // ★★★ 新增邏輯：在上傳新圖前，先刪除舊的主圖 (Index 0) ★★★
        if (isset($currentImages[0]) && !empty($currentImages[0])) {
            // 你的資料庫存的是 "Shop/xxx.jpg"，但 PHP 操作檔案需要相對路徑 "../Shop/xxx.jpg"
            $oldFilePath = '../' . $currentImages[0];
            
            // 檢查檔案是否存在，存在就刪除
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
        // ★★★ 刪除邏輯結束 ★★★

        $ext = pathinfo($_FILES['mainImage']['name'], PATHINFO_EXTENSION);
        $newFilename = uniqid('main_') . '.' . $ext;
        
        if (move_uploaded_file($_FILES['mainImage']['tmp_name'], $uploadDir . $newFilename)) {
            // 加上 "Shop/" 前綴並覆蓋陣列第 0 個位置
            $currentImages[0] = 'Shop/' . $newFilename;
        }
    }

    // ====================================================
    // 3. 處理新副圖 (Sub Images)
    // ====================================================
    // 如果使用者有上傳新副圖，就 "追加" 到陣列後面
    if (isset($_FILES['subImages'])) {
        $count = count($_FILES['subImages']['name']);
        
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['subImages']['error'][$i] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['subImages']['name'][$i], PATHINFO_EXTENSION);
                $newFilename = uniqid('sub_') . '.' . $ext;
                
                if (move_uploaded_file($_FILES['subImages']['tmp_name'][$i], $uploadDir . $newFilename)) {
                    $currentImages[] = 'Shop/' . $newFilename; // push 進陣列
                }
            }
        }
    }

    // 重新組合成字串，並過濾掉空值 (array_values 確保索引重排)
    $finalImageString = implode('|', array_values(array_filter($currentImages)));

    // ====================================================
    // 4. 更新 product 主表
    // ====================================================
    $sql_product = "UPDATE product SET 
            name_zh = ?,
            name_en = ?,
            type_zh = ?,
            type_en = ?,
            price = ?,
            stock = ?,
            product_status = ?,  
            image = ?,
            update_at = NOW() 
            WHERE product_ID = ?";

    $stmt1 = $pdo->prepare($sql_product);
    $stmt1->execute([
        $_POST['nameZh'] ?? '',
        $_POST['nameEn'] ?? '',
        $_POST['typeZh'] ?? '',
        $_POST['typeEn'] ?? '',
        $_POST['price'] ?? 0,
        $_POST['stock'] ?? 0,
        $_POST['status'] ?? 0,
        $finalImageString,
        $id
    ]);

    // ====================================================
    // 5. 更新 product_detail 明細表
    // ====================================================
    $sql_detail = "UPDATE product_detail SET 
            description_zh = ?,
            description_en = ?,
            story_zh = ?,
            story_en = ?,
            use_zh = ?,
            use_en = ?
            WHERE product_ID = ?";

    $stmt2 = $pdo->prepare($sql_detail);
    $stmt2->execute([
        $_POST['descriptionZh'] ?? '',
        $_POST['descriptionEn'] ?? '',
        $_POST['storyZh'] ?? '',
        $_POST['storyEn'] ?? '',
        $_POST['useZh'] ?? '',
        $_POST['useEn'] ?? '',
        $id
    ]);

    $response['success'] = true;
    $response['message'] = '商品更新成功';

} catch (Exception $e) {
    http_response_code(500);
    $response['message'] = '更新失敗: ' . $e->getMessage();
}

echo json_encode($response);
?>
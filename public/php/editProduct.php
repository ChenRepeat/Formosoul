<?php

require_once 'conn.php';

$response = ['success' => false, 'message' => ''];

try {
    if (!isset($_POST['product_ID']) || empty($_POST['product_ID'])) {
        throw new Exception('缺少商品 ID');
    }

    $id = $_POST['product_ID'];

    // ====================================================
    // 1. 圖片處理 (與原本相同)
    // ====================================================
    function uploadImage($file, $targetDir = '../img/Shop/') {
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFilename = uniqid('prod_') . '.' . $extension;
        $targetPath = $targetDir . $newFilename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'Shop/' . $newFilename;
        }
        return false;
    }

    // 撈取舊圖
    $stmt_old = $pdo->prepare("SELECT image FROM product WHERE product_ID = ?");
    $stmt_old->execute([$id]);
    $row = $stmt_old->fetch(PDO::FETCH_ASSOC);
    $oldImageString = $row['image'] ?? '';
    $currentImages = !empty($oldImageString) ? explode('|', $oldImageString) : [];

    // 處理新主圖
    if (isset($_FILES['mainImage']) && $_FILES['mainImage']['error'] === UPLOAD_ERR_OK) {
        $newMainPath = uploadImage($_FILES['mainImage']);
        if ($newMainPath) {
            $currentImages[0] = $newMainPath; // 取代第 0 張
        }
    }

    // 處理新副圖
    if (isset($_FILES['subImages'])) {
        $count = count($_FILES['subImages']['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['subImages']['error'][$i] === UPLOAD_ERR_OK) {
                $file = [
                    'name'     => $_FILES['subImages']['name'][$i],
                    'tmp_name' => $_FILES['subImages']['tmp_name'][$i],
                    'error'    => $_FILES['subImages']['error'][$i],
                    'size'     => $_FILES['subImages']['size'][$i]
                ];
                $newSubPath = uploadImage($file);
                if ($newSubPath) $currentImages[] = $newSubPath;
            }
        }
    }
    $finalImageString = implode('|', array_values(array_filter($currentImages)));

    // ====================================================
    // 2. 更新第一張表：product (主檔)
    // ====================================================
    // 這裡只更新 product 表有的欄位
    // 注意：記得確認你的狀態欄位是 status 還是 product_status
    $sql_product = "UPDATE product SET 
            name_zh = ?,
            name_en = ?,
            type_zh = ?,
            type_en = ?,
            price = ?,
            stock = ?,
            product_status = ?,  
            image = ?       
            WHERE product_ID = ?";

    $stmt1 = $pdo->prepare($sql_product);
    $stmt1->execute([
        $_POST['nameZh'] ?? '',
        $_POST['nameEn'] ?? '',
        $_POST['typeZh'] ?? '',
        $_POST['typeEn'] ?? '',
        $_POST['price'] ?? 0,
        $_POST['stock'] ?? 0,
        $_POST['status'] ?? 0, // 這裡對應前端傳來的 status
        $finalImageString,
        $id
    ]);

    // ====================================================
    // 3. 更新第二張表：product_detail (明細檔)
    // ====================================================
    // 這裡更新 描述、故事、玩法
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
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
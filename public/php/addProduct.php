<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'conn.php';

try {
    // 0. 基本檢查
    if (empty($_POST['id'])) {
        throw new Exception('必須填寫商品編號 (ID)');
    }
    $customID = $_POST['id'];

    // 交易開始
    $pdo->beginTransaction();

    // ===========================================================
    // ★ 關鍵步驟：先檢查 ID 是否已存在
    // ===========================================================
    $checkSql = "SELECT COUNT(*) FROM product WHERE product_ID = ?";
    $stmtCheck = $pdo->prepare($checkSql);
    $stmtCheck->execute([$customID]);
    
    if ($stmtCheck->fetchColumn() > 0) {
        throw new Exception("編號 '{$customID}' 已經存在，請換一個編號。");
    }

    // ===========================================================
    // 第一步：寫入 product 主表 (加入 product_ID 欄位)
    // ===========================================================
    $sqlProduct = "INSERT INTO product (
        product_ID, name_zh, name_en, type_zh, type_en, price, stock, create_at, update_at, product_status,`image`
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?,''
    )";

    $stmtProduct = $pdo->prepare($sqlProduct);
    $stmtProduct->execute([
        $customID,           // ★ 這裡手動填入 ID
        $_POST['nameZh'],
        $_POST['nameEn'],
        $_POST['typeZh'],
        $_POST['typeEn'],
        $_POST['price'],
        $_POST['stock'],
        $_POST['status']     // 記得前端傳來的是 status 還是 product_status，請對應好
    ]);

    // ★ 自訂ID模式下，不需要也不會有 lastInsertId()，直接用 $customID
    $newProductID = $customID; 

    // ===========================================================
    // 第二步：寫入 product_detail
    // ===========================================================
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

    // ===========================================================
    // 第三步：處理圖片上傳 (邏輯不變)
    // ===========================================================
    $uploadDir = '../Shop/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadedImages = [];

    // 主圖
    if (isset($_FILES['mainImage']) && $_FILES['mainImage']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['mainImage']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('main_') . '.' . $ext;
        if (move_uploaded_file($_FILES['mainImage']['tmp_name'], $uploadDir . $fileName)) {
            $uploadedImages[] = 'Shop/' . $fileName;
        }
    }

    // 副圖
    if (isset($_FILES['subImages'])) {
        $count = count($_FILES['subImages']['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['subImages']['error'][$i] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['subImages']['name'][$i], PATHINFO_EXTENSION);
                $fileName = uniqid('sub_') . '.' . $ext;
                if (move_uploaded_file($_FILES['subImages']['tmp_name'][$i], $uploadDir . $fileName)) {
                    $uploadedImages[] = 'Shop/' . $fileName;
                }
            }
        }
    }

    // ===========================================================
    // 第四步：更新圖片欄位
    // ===========================================================
    if (!empty($uploadedImages)) {
        $imageString = implode('|', $uploadedImages);
        $sqlUpdate = "UPDATE product SET image = ? WHERE product_ID = ?";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([$imageString, $newProductID]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true, 
        'message' => '新增成功',
        'id' => $newProductID
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // 回傳具體錯誤訊息 (例如 ID 重複)
    echo json_encode(['success' => false, 'message' => '處理失敗: ' . $e->getMessage()]);
}
?>
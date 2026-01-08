<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'conn.php';

try {
    if (empty($_POST['typeEn'])) {
        throw new Exception('必須填寫商品分類英文代號 (typeEn)');
    }

    // 處理分類縮寫
    // 原始完整分類 (例如 Folktoys)，保留給資料庫欄位用
    $originTypeEn = $_POST['typeEn']; 

    // 取前兩個字元並轉大寫 (例如 Folktoys -> FO)
    // substr(字串, 起始位置, 長度)
    $typeCode = strtoupper(substr($originTypeEn, 0, 2)); 

    // 交易開始
    $pdo->beginTransaction();

    // ===========================================================
    // ★ 核心修改：自動產生 product_ID
    // 格式：[前兩碼分類][YYYYMM][0001~9999]  EX: FO2026010001
    // ===========================================================
    
    // 1. 組合前綴 (Prefix)
    $currentYM = date('Ym'); // 取得當前年月，如 202601
    
    // ★ 這裡改用剛剛截取的兩碼代號 $typeCode
    $prefix = $typeCode . $currentYM; // 組合，如 FO202601

    // 2. 查詢該月份目前最大的編號
    $sqlGetMax = "SELECT product_ID FROM product 
                  WHERE product_ID LIKE ? 
                  ORDER BY product_ID DESC 
                  LIMIT 1 FOR UPDATE"; 
    
    $stmtMax = $pdo->prepare($sqlGetMax);
    $stmtMax->execute([$prefix . '%']);
    $rowMax = $stmtMax->fetch(PDO::FETCH_ASSOC);

    if ($rowMax) {
        // --- 情況 A：這個月該分類已經有商品 (例如 FO2026010005) ---
        $lastID = $rowMax['product_ID'];
        
        // 取出最後 4 碼流水號
        $lastSeqStr = substr($lastID, -4); 
        
        // 轉數字 + 1
        $newSeq = intval($lastSeqStr) + 1;
    } else {
        // --- 情況 B：這個月該分類還沒商品 (是第一筆) ---
        $newSeq = 1;
    }

    // 3. 補零組合成完整 ID (例如 FO2026010006)
    $newProductID = $prefix . str_pad($newSeq, 4, '0', STR_PAD_LEFT);


    // ===========================================================
    // 第一步：寫入 product 主表
    // ===========================================================
    $sqlProduct = "INSERT INTO product (
        product_ID, name_zh, name_en, type_zh, type_en, price, stock, create_at, update_at, product_status, `image`
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ''
    )";

    $stmtProduct = $pdo->prepare($sqlProduct);
    $stmtProduct->execute([
        $newProductID,       // ★ 使用縮寫產生出的 ID (FO202601xxxx)
        $_POST['nameZh'],
        $_POST['nameEn'],
        $_POST['typeZh'],
        $_POST['typeEn'],    // ★ 資料庫的 type_en 欄位通常存完整單字 (Folktoys)，所以這裡用原始值
        $_POST['price'],
        $_POST['stock'],
        $_POST['status']
    ]);

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
    // 第三步：處理圖片上傳
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
    echo json_encode(['success' => false, 'message' => '處理失敗: ' . $e->getMessage()]);
}
?>
<?php
require_once 'conn.php';


$response = ['success' => false, 'message' => ''];

try {
    // 檢查是否有傳入 ID
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;

    if (!$id) {
        throw new Exception('未提供商品 ID');
    }

    // 3. 開啟交易模式 (Transaction) - 確保主表跟明細表同時刪除
    $pdo->beginTransaction();


    // 先撈出圖片路徑，刪除實體檔案
    $sql_get_img = "SELECT image FROM product WHERE product_ID = ?";
    $stmt_img = $pdo->prepare($sql_get_img);
    $stmt_img->execute([$id]);
    $imgString = $stmt_img->fetchColumn(); // 取得 "Shop/a.jpg|Shop/b.jpg"

    if ($imgString) {
        $images = explode('|', $imgString);
        foreach ($images as $img) {

            $filePath = '../' . $img; 

            // 檢查檔案存在才刪除
            if (file_exists($filePath)) {
                unlink($filePath); // 刪除檔案指令
            }
        }
    }

    // (A) 先刪除明細表 (product_detail)
    $sql_detail = "DELETE FROM product_detail WHERE product_ID = ?";
    $stmt_detail = $pdo->prepare($sql_detail);
    $stmt_detail->execute([$id]);

    // (B) 再刪除主表 (product)
    $sql_product = "DELETE FROM product WHERE product_ID = ?";
    $stmt_product = $pdo->prepare($sql_product);
    $stmt_product->execute([$id]);

    // 4. 提交交易 (確認刪除)
    $pdo->commit();

    $response['success'] = true;
    $response['message'] = '商品刪除成功';

} catch (Exception $e) {
    // 如果出錯，回滾交易 (復原剛剛的動作)
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    http_response_code(500);
    $response['message'] = '刪除失敗：' . $e->getMessage();
}

echo json_encode($response);
?>
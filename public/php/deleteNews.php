<?php
require_once 'conn.php';

$response = ['success' => false, 'message' => ''];

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;

    if (!$id) {
        throw new Exception('未提供消息 ID');
    }

    //  先撈出圖片路徑，刪除實體檔案
    $sql_get_img = "SELECT pic FROM news WHERE newsID = ?";
    $stmt_img = $pdo->prepare($sql_get_img);
    $stmt_img->execute([$id]);
    $imgString = $stmt_img->fetchColumn();

    if ($imgString) {
        $filePath = '../' . $imgString; 
        
        // 檢查檔案存在才刪除，避免報錯
        if (file_exists($filePath)) {
            unlink($filePath); 
        }
    }

    // 刪除資料庫紀錄
    $sql_delete = "DELETE FROM news WHERE newsID = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([$id]);


    $response['success'] = true;
    $response['message'] = '消息刪除成功';

} catch (Exception $e) { 
    http_response_code(500);
    $response['message'] = '刪除失敗：' . $e->getMessage();
}

echo json_encode($response);
?>
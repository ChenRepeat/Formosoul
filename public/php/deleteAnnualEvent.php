<?php
require_once 'conn.php';

$response = ['success' => false, 'message' => ''];

try {

    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;

    if (!$id) {
        throw new Exception('未提供活動 ID');
    }


    $sql_get_img = "SELECT pic FROM annalevent WHERE annalevent_ID = ?";
    $stmt_img = $pdo->prepare($sql_get_img);
    $stmt_img->execute([$id]);
    $imgString = $stmt_img->fetchColumn();

    if ($imgString) {
        // 資料庫存的是 "festivals/xxx.jpg"，加上 "../" 指向正確路徑
        $filePath = '../' . $imgString; 
        
        // 檢查檔案存在才刪除
        if (file_exists($filePath)) {
            unlink($filePath); 
        }
    }

    $sql_delete = "DELETE FROM annalevent WHERE annalevent_ID = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([$id]);

    $response['success'] = true;
    $response['message'] = '年度盛事刪除成功';

} catch (Exception $e) { 
    http_response_code(500);
    $response['message'] = '刪除失敗：' . $e->getMessage();
}

echo json_encode($response);
?>
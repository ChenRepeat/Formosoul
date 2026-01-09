<?php

require 'conn.php';

try {
    // 接收 JSON 資料
    // 不能用 $_POST，必須用 file_get_contents('php://input')
    $input = json_decode(file_get_contents('php://input'), true);

    if (empty($input['id'])) {
        throw new Exception('缺少必要欄位 (ID)');
    }

    $memberID = $input['id'];
    
    $sql = "UPDATE member SET 
            `role` = ?, 
            `status` = ?
            WHERE member_ID = ?";

    $stmt = $pdo->prepare($sql);
    
    // 執行更新
    $stmt->execute([
        $input['role'] ?? 0,             // 若沒傳則預設 0
        $input['status'] ?? 1,           // 若沒傳則預設 1
        $memberID
    ]);

    // 4. 回傳成功訊息
    echo json_encode([
        'success' => true, 
        'message' => '會員權限與狀態更新成功',
        'id' => $memberID
    ]);

} catch (Exception $e) {
    // 5. 錯誤處理
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => '更新失敗: ' . $e->getMessage()
    ]);
}
?>
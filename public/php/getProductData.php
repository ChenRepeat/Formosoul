  <?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

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
  $rows = $stmt->fetchAll();


    // 告訴瀏覽器是純資料 (JSON)
    header('Content-Type: application/json; charset=utf-8');

    // 把 PHP 陣列 ($rows) 轉成 JSON 字串並傳到前端
    echo json_encode($rows);

  ?>
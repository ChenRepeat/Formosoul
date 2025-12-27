  <?php
  require_once 'conn.php';

 $sql = "
  select * from `order`
";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $rows = $stmt->fetchAll();


    // 告訴瀏覽器是純資料 (JSON)
    header('Content-Type: application/json; charset=utf-8');

    // 把 PHP 陣列 ($rows) 轉成 JSON 字串並傳到前端
    echo json_encode($rows);

  ?>
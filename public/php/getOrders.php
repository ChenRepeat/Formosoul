  <?php
  require_once 'conn.php';

 $sql = "
  select * from `order`
";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $rows = $stmt->fetchAll();

    echo json_encode($rows);

  ?>
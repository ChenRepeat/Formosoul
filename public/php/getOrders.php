  <?php
  require_once 'conn.php';

 $sql = "
  SELECT * FROM `order` ORDER BY `date` DESC
";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rows);

  ?>
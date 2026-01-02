 <?php
  require_once 'conn.php';


  $sql = 'SELECT 
  member_ID, 
  `name`, 
  email, 
  pointscard, 
  createdate, 
  `role`, 
  `status` 
  FROM member';

$stmt = $pdo->prepare($sql);
$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($rows);

  ?>
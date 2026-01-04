  <?php
  require_once 'conn.php';
//   $member = json_decode(file_get_contents("php://input"), true);

  $sql = '
    SELECT * 
    FROM wandcore;
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $count = $stmt->rowCount();
    $wandcore = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($wandcore) {
        echo json_encode([
        'success' => true,
        'data' => $wandcore,
        'length' => $count,
      ]);
    }else {
        echo json_encode(['success' => false, 'message' => 'no wandcore']);
    }



  ?>
  <?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  $sql = '
    INSERT INTO member(email, password, status, role, pointscard, createdate, updatetime)
    VALUES
    (:email, :password, 1, 0, 0, NOW(), NOW())
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $member['email']);
    $stmt->bindValue(':password', $member['password']);
    $stmt->execute();
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode(['success' => true]);


  ?>
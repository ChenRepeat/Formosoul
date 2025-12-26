  <?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  session_start();

  $checksql = '
    SELECT COUNT(*) 
    FROM member 
    WHERE email = :email
  ';
  $checkstmt = $pdo->prepare($checksql);
  $checkstmt->bindValue(':email', $member['email']);
  $checkstmt->execute();
  // fetchColumn 可以只拿單一欄位
  $count = $checkstmt->fetchColumn();

  if($count > 0){
    echo json_encode([
        'success' => false,
        'message' => 'This email address is already registered.'
    ]);
    exit;
  }

  if(!isset($_SESSION['otp']) || $member['otp'] !== $_SESSION['otp'] || $member['email'] !== $_SESSION['otp_email']){
    echo json_encode([
      'success' => false,
      'message' => 'erification code error or please obtain verification code first.'
    ]);

    exit;
  }
  if(time() - $_SESSION['otp_time'] > 600){
    echo json_encode([
      'success' => false,
      'message' => 'The verification code has expired. Please obtain a new one.'
    ]);

    exit;
  }

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
    unset($_SESSION['otp']);
    unset($_SESSION['otp_email']);
    echo json_encode(['success' => true]);


  ?>
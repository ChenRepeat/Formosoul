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
  //'INSERT INTO member(email, password, status, role, pointscard, createdate, updatetime)
  //  VALUES (:email, :password, 1, 0, 0, NOW(), NOW());'
  $sql = 
  '
  START TRANSACTION;
	  INSERT INTO member(email, password, status, role, pointscard, createdate, updatetime)
      VALUES (:email, :password , 1, 0, 0, NOW(), NOW());
    SET @USER_ID = LAST_INSERT_ID();
	  INSERT INTO pointscard (member_ID,count,mot,shrimp,dice,ring,bue,member_wandcore)
	    VALUES (@USER_ID,0,0,0,0,0,0,0);
    SET @CARD_ID = LAST_INSERT_ID();
    INSERT INTO buegame (buegame_count, pointscard_ID, buegame_pass)
	    VALUES (0,@CARD_ID,0);
    INSERT INTO charmgame (member_ID, charmgame_img1, charmgame_count)
	    VALUES (@USER_ID,0,0);
    INSERT INTO dicegame (pointscard_ID, dicegame_count, dicegame_pass)
	    VALUES (@CARD_ID,0,0);
    INSERT INTO motorcyclegame (pointscard_ID, motorcyclegame_count, motorcyclegame_score, motorcyclegame_pass)
	    VALUES (@CARD_ID,0,0,0);
    INSERT INTO ringgame (pointscard_ID, ringgame_count, ringgame_score, ringgame_pass)
	    VALUES (@CARD_ID,0,0,0);
    INSERT INTO shrimpgame (pointscard_ID, shrimpgame_count, shrimpgame_score, shrimpgame_pass)
	    VALUES (@CARD_ID,0,0,0);
  COMMIT; 
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $member['email']);
    $stmt->bindValue(':password', $member['password']);
    $stmt->execute();
    unset($_SESSION['otp']);
    unset($_SESSION['otp_email']);
    echo json_encode(['success' => true]);


  ?>
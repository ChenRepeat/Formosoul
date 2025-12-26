  <?php
  require_once 'conn.php';
  $data = json_decode(file_get_contents("php://input"), true);

  session_start();

  $email = $data['email'] ?? '';
  $otp = $data['otp'] ?? '';

  if(!empty($email) && !empty($otp)){
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_email'] = $email;
    $_SESSION['otp_time'] = time();
    echo json_encode([
        'success' => true,
        'message' => 'OTP save in seesion'
    ]);    
  }else{
    echo json_encode([
        'success' => false,
        'message' => 'Invalid data'
    ]);
  }




  ?>
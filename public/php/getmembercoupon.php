  <?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);
    // session_start();

  $sql = '
    select 
        uc.pointscard_ID,
        uc.coupons_ID,
        uc.status,
        uc.used_at,
        r.coupons_id,
        r.game_name,
        p.pointscard_ID,
        p.member_ID,
        c.discount,
        c.threshold,
        DATE_FORMAT(c.enddate, \'%Y-%m-%d\') AS end_date
    from formosoul.user_coupons uc
    left join  formosoul.game_coupon_rewards r on r.coupons_id = uc.coupons_ID
    left join  formosoul.pointscard p on p.pointscard_ID = uc.pointscard_ID
    left join  formosoul.coupons c on c.coupons_ID = uc.coupons_ID
    WHERE p.member_ID = :member_ID AND p.pointscard_ID = :pointscard_ID;
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    $stmt->bindValue(':pointscard_ID', $member['pointscard_ID']);
    $stmt->execute();
    $member_coupon = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($member_coupon) {
        // $_SESSION['current_coupon'] = $member_coupon;
        echo json_encode([
        'success' => true,
        'data' => $member_coupon,
      ]);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到會員']);
    }



  ?>
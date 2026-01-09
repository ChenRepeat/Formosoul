  <?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  $couponsql = '
      SELECT
        o.order_number,
        o.shipping,
        c.name AS coupon_name,
        c.discount
      FROM `order` o
      LEFT JOIN coupons c ON o.coupons_ID = c.coupons_ID
      WHERE o.member_ID = :member_ID;
  ';
    $couponsql = $pdo->prepare($couponsql);
    $couponsql->bindValue(':member_ID', $member['member_ID']);
    $couponsql->execute();
    $coupon_discount = $couponsql->fetchAll(PDO::FETCH_ASSOC);
  // COALESCE(SUM(p.price * d.quantity), 0) COALESCE(a, b) 如果a是null 就回傳b 
  $sql = '
      SELECT
          o.order_number,
          DATE_FORMAT(o.date, "%Y-%m-%d") AS orderdate,
          o.payment,
          o.status,
          o.name_en,
          o.shipping,
          o.total_amount,
          COALESCE(SUM(d.price * d.quantity), 0) as subtotal
      FROM `order` o
      LEFT JOIN order_detail d ON o.order_number = d.order_no
      LEFT JOIN product p ON p.product_ID = d.product_ID
      WHERE member_ID = :member_ID
      GROUP BY o.order_id
      ORDER BY o.date DESC, o.order_id DESC;
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    $stmt->execute();
    $order_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($order_data && $coupon_discount) {
        echo json_encode([
        'success' => true,
        'data' => $order_data,
        'coupon' => $coupon_discount,
      ]);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到訂單']);
    }



  ?>
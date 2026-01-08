  <?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  $countsql = '
      SELECT
        o.order_number,
        o.total_amount,
        d.quantity,
		    d.price,
        d.price_sum,
        p.name_en
      FROM `order` o
      LEFT JOIN order_detail d ON o.order_number = d.order_no
      LEFT JOIN product p ON p.product_ID = d.product_ID
      WHERE o.member_ID = :member_ID AND o.order_number = :order_number;
  ';

    $countstmt = $pdo->prepare($countsql);
    $countstmt->bindValue(':member_ID', $member['member_ID']);
    $countstmt->bindValue(':order_number', $member['order_number']);
    $countstmt->execute();
    $order_count = $countstmt->fetchAll(PDO::FETCH_ASSOC);
  $sql = '
      SELECT
        o.order_number,
        DATE_FORMAT(o.date, "%Y-%m-%d") AS orderdate,
        o.payment,
        o.status,
        o.name_en,
        o.shipping,
        o.address_en,
        o.remark,     
        o.discount,
        o.total_amount
      FROM `order` o
      WHERE o.member_ID = :member_ID AND o.order_number = :order_number;
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    $stmt->bindValue(':order_number', $member['order_number']);
    $stmt->execute();
    $order_information = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($order_information && $order_count) {
        echo json_encode([
        'success' => true,
        'data' => $order_information,
        'count' => $order_count,
        // $order_information
      ]);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到訂單']);
    }



  ?>
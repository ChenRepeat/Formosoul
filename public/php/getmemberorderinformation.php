  <?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  $sql = '
    SELECT
        o.order_number,
        o.date,
        o.payment,
        o.status,
        o.name_en,
        o.shipping,
        o.address_en,
        o.remark,
        d.quantity,
        d.price
      FROM formosoul.order o
      LEFT JOIN order_detail d ON o.order_ID = d.order_ID
      WHERE o.member_ID = :member_ID AND o.order_number = :order_number;
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    $stmt->bindValue(':order_number', $member['order_number']);
    $stmt->execute();
    $order_information = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($order_information) {
        echo json_encode([
        'success' => true,
        'data' => $order_information,
        // $order_information
      ]);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到訂單']);
    }



  ?>
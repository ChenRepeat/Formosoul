  <?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);


  $sql = '
	SELECT
        o.order_number,
        d.product_ID,
        d.quantity,
        p.price,
        p.name_en,
        i.is_main,
        i.url
	FROM formosoul.order_detail d
    LEFT JOIN formosoul.product p ON p.product_ID = d.product_ID
    LEFT JOIN formosoul.product_images i ON p.product_ID = i.product_ID
    LEFT JOIN formosoul.order o ON o.order_ID = d.order_ID
    WHERE o.member_ID = :member_ID AND o.order_number = :order_number AND is_main = 1;
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    $stmt->bindValue(':order_number', $member['order_number']);
    $stmt->execute();
    $order_product = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($order_product ) {
        echo json_encode([
        'success' => true,
        'data' => $order_product,
      ]);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到訂單']);
    }



  ?>
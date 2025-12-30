  <?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  $sql = '
      SELECT
          order_number,
          date,
          payment,
          status,
          name_en,
          shipping
        FROM formosoul.order
        WHERE member_ID = :member_ID;s
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    $stmt->execute();
    $order_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($order_data) {
        echo json_encode([
        'success' => true,
        'data' => $order_data,
        // 'order' =>
      ]);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到訂單']);
    }



  ?>
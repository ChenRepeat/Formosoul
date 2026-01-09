  <?php
  require_once 'conn.php';
  $member = json_decode(file_get_contents("php://input"), true);

  $sql = '
        SELECT
            c.member_ID,
            c.product_ID,
            c.collect_status,
            p.image,
            p.price,
            p.type_en,
            p.type_zh,
            p.name_en,
            p.name_zh
        FROM collection c
        LEFT JOIN product p ON p.product_ID = c.product_ID
        WHERE member_ID = :member_ID;
  ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':member_ID', $member['member_ID']);
    $stmt->execute();
    $member_collection = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($member_collection) {
        echo json_encode([
        'success' => true,
        'havecoupon' => true,
        'data' => $member_collection,
      ]);
    }else {
        echo json_encode(['success' => false, 'message' => '找不到收藏列表']);
    }



  ?>
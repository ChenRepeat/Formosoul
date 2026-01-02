<?php
// 連線
require_once 'conn.php';

// SQL
$sql = "select * 
        from product p
	    left join product_detail pd
        on p.product_ID = pd.product_ID;";









?>
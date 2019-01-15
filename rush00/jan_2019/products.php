<?php
session_start();
include_once 'navbar.php';
include_once 'minishop.php';
$link = db_connect();
if (!$link) {
    die("mysqli connect failed");
}

$query = mysqli_query($link, 'SELECT id FROM products WHERE active=1;');
if (!$query) {
    mysqli_close($link);
    die("mysqli query failed");
}
$product_ids = [];
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    $product_ids[] = $row['id'];
}
foreach ($product_ids as $id) {
    echo make_html_product_card($id);
}
mysqli_close($link);
?>
</body>
</html>

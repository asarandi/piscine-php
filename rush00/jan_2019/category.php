<?php
session_start();
include_once 'navbar.php';
include_once 'minishop.php';
$link = db_connect();
if (!$link) {
    die("mysqli connect failed");
}
$category_id = 1;
if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
    $category_id = mysqli_real_escape_string($link, $_GET['category_id']);
}

$query = mysqli_query($link, 'SELECT product_id FROM category_items WHERE active=1 and category_id='.$category_id.';');
if (!$query) {
    mysqli_close($link);
    die("mysqli query failed");
}
$product_ids = [];
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    $product_ids[] = $row['product_id'];
}
foreach ($product_ids as $id) {
    echo make_html_product_card($id);
}
mysqli_close($link);
?>
</body>
</html>

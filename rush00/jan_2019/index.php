<?php
session_start();
include 'navbar.php';   //contans html, head, body
include 'minishop.php';
$link = db_connect();
if (!$link) {
    die("mysqli connect failed");
}
$query = mysqli_query($link, 'SELECT id, title, description, photo FROM categories WHERE active=1;');
if (!$query) {
    die("mysqli query failed");
}
$categories = [];
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    $categories[] = $row;
}
mysqli_close($link);
$CATEGORY_IMAGE_STYLE = 'width="750" height="375"';
foreach ($categories as $category) {
    $f  = sprintf('<h2>%s</h2>', $category['title']);
    $f .= sprintf('<a href="category.php?category_id=%d">', $category['id']);
    $f .= sprintf('<img src="%s" alt="%s image" %s></a>', $category['photo'], $category['title'], $CATEGORY_IMAGE_STYLE);
    $f .= sprintf('<h5>%s</h5><hr>', $category['description']);
    echo $f;
}
?>
</body>
</html>

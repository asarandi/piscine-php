<?php
  include 'subroutines.php';
  $id = -1;
  if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
  } else {
    $id = -1;
  };


    $db = mysql_connect();
    $query = "SELECT name, short_description, description, pictures FROM categories WHERE id='$id' AND active=1;";
    if ($id == -1)
        $query = "SELECT name, short_description, description, pictures FROM categories WHERE active=1;";
    //$query = "SELECT title, description, pictures, author, isbn13, publication_date, price FROM products WHERE id='$id';";

    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    foreach ($row as $key => $value) {
      $row[$key] = trim(stripslashes($value));
    }
    mysqli_free_result($result);
    $category = $row;

    $PICTURES_SEPARATOR = ';';

    $img = trim(explode($PICTURES_SEPARATOR, $row['pictures'])[0]);
    $alt = trim(explode('.', basename($img))[0]);

    $query = "SELECT id, title, pictures, author, price FROM products WHERE categories LIKE '%".strtolower($category['name'])."%';";

    $result = mysqli_query($db, $query);
    $products = [];

    while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) !== NULL) {
      foreach ($row as $key => $value) {
        $row[$key] = trim(stripslashes($value));
      }
      $item_img = trim(explode($PICTURES_SEPARATOR, $row['pictures'])[0]);
      $item_alt = trim(explode('.', basename($item_img))[0]);
      $row['img'] = $item_img;
      $row['alt'] = $item_alt;

      $products[] = $row;
  }
  mysqli_free_result($result);
  mysqli_close($db);
?>
<!DOCTYPE html>
<html>
<head>
    <title>BookStore Genre</title>
    <link rel="stylesheet" type="text/css" href="homepage.css">
</head>
<body>
<?php show_sidenav(); show_topbar(); show_modal(); ?>
<hr style="margin-top: -20px;">
<div class="main"">
    <h1 style="text-align: center; font-size: 2.5vw";><?php echo $category['name'];?></h1>
    <p style="font-size: medium; text-align: center;"><i><?php echo $category['short_description'];?></i></p>
    <img src="<?php echo $img;?>" alt="<?php echo $alt;?>" width="100%">
    <p style="font-size: 1.7vw; text-align: center;"><?php echo $category['description'];?></p>
</div>
<hr>
<?php
$flag = 0;
for ($i = 0; $i < count($products); $i++){
	if ($i % 3 == 0){
		if ($i > 0) {
			echo '</div>' . "\n";
		}
		echo '<div class="row main">' . "\n";
  }
    echo '    <div class="column">' . "\n";
    echo '        <div class="card">' . "\n";
    echo '            <a href="product.php?id='.$products[$i]['id'].'"><img src="'.$products[$i]['img'].'" alt="'.$products[$i]['alt'].'" style="width:80%; margin-left: 10%;"></a>' . "\n";
    echo '            <div class="container">' . "\n";
    echo '                <h2>'.$products[$i]['title'].'</h2>' . "\n";
    echo '                <p class="author">'.$products[$i]['author'].'</p>' . "\n";
	echo '                <p>'.$products[$i]['price'].'</p>' . "\n";
	echo '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">' . "\n";
	echo '<input type="hidden" id="add_to_basket" name="add_to_basket" value="'.$products[$i]['id'].'">' . "\n";
	echo '                <p><button class="button">Add To Bag</button></p>' . "\n";
	echo '</form>' . "\n";
    echo '            </div>' . "\n";
    echo '        </div>' . "\n";
    echo '    </div>' . "\n";
}
if ($i > 0)
	echo '</div>' . "\n";
  echo "<hr>\n";
  show_footer();
?>
</body>
</html>

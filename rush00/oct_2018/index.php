<!DOCTYPE html>
<html>
<head>
    <title>BookStore HomePage</title>
<link rel="stylesheet" type="text/css" href="homepage.css">
</head>
<body>
<?php include 'subroutines.php'; show_sidenav(); show_topbar(); show_modal(); ?>
<hr style="margin-top: -20px;">
<div class="row main">
<?php
	$SEPARATOR = ';';

   $db = mysql_connect();

    $query = 'SELECT id, title, author, price, pictures FROM products WHERE active=1 ORDER BY title ASC;';
    $result = mysqli_query($db, $query);
    $products = [];

	while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) !== NULL) {
		foreach ($row as $key => $value) {
			$row[$key] = trim(stripslashes($value));
		}
		$products[] = $row;
    }
    mysqli_free_result($result);
	mysqli_close($db);

	$ITEMS_ON_PAGE = 3;
	$randoms = [];

	while (true) {
		if (count($randoms) >= $ITEMS_ON_PAGE)
			break ;
		$random = random_in_range(0, count($products) - 1);
		if (!in_array($random, $randoms))
			$randoms[] = $random;
	}

	foreach ($randoms as $i) {

		$id = $products[$i]['id'];
		$img = trim(explode($SEPARATOR, $products[$i]['pictures'])[0]);
		$alt = trim(explode('.', basename($img))[0]);
		$title = $products[$i]['title'];
		$author = $products[$i]['author'];
		$price = $products[$i]['price'];

		echo '   <div class="column">' . "\n";
		echo '       <div class="card">' . "\n";
		echo '           <a href="product.php?id='.$id.'">' . "\n";
		echo '               <img src="'.$img.'" alt="'.$alt.'" style="width:80%; margin-left: 10%;">' . "\n";
		echo '           </a>' . "\n";
		echo '           <div class="container">' . "\n";
		echo '               <h2 class="title">'.$title.'</h2>' . "\n";
		echo '               <p class="author">'.$author.'</p>' . "\n";
		echo '               <p>'.$price.'</p>' . "\n";
		echo '               <p><button class="button">Add To Bag</button></p>' . "\n";
		echo '           </div>' . "\n";
		echo '       </div>' . "\n";
		echo '   </div>' . "\n";
	}
?>
</div>
<hr>
<?php show_footer();?>
</body>
</html>

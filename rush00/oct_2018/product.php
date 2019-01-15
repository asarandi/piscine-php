<?php
include 'subroutines.php';

$SEPARATOR = ';';
$title = '';
$description = '';
$img = '';
$alt = '';
$author = '';
$isbn13 = '';
$publication_date = '';
$price = '';

$id = 1;
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
} else {
  $id = 1;
}

  $db = mysql_connect();
  $query = "SELECT title, description, pictures, author, isbn13, publication_date, price FROM products WHERE id='$id' AND active=1;";

  $result = mysqli_query($db, $query);
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  foreach ($row as $key => $value) {
    $row[$key] = trim(stripslashes($value));
  }
  mysqli_free_result($result);
  mysqli_close($db);

  $img = trim(explode($SEPARATOR, $row['pictures'])[0]);
  $alt = trim(explode('.', basename($img))[0]);
?>
<!DOCTYPE html>
<html>
<head>
    <title>BookStore Product</title>
    <link rel="stylesheet" type="text/css" href="homepage.css">
</head>
<body>
<?php show_sidenav(); show_topbar(); show_modal(); ?>
<hr style="margin-top: -20px;">
<div class="main">
    <h1><center><?php echo $row['title'];?></center></h1>
    <hr width="50%">
</div>
<div class="row main">
    <div class="column">
        <div class="card">
            <div class="container">
                <p class="descript"><?php echo $row['description'];?></p>
            </div>
        </div>
    </div>
    <div class="column">
        <div class="card">
            <img src="<?php echo $img;?>" alt="<?php echo $alt;?>" style="width:100%;">
            <div class="container">
            </div>
        </div>
    </div>
    <div class="column">
        <div class="card">
            <div class="container" style="padding: 15%">
                <h2 class="title"><?php echo $row['title'];?></h2>
                <p class="author">by <?php echo $row['author'];?></p>
                <p class="descript">ISBN: <?php echo $row['isbn13'];?></p>
                <p class="descript">Publication date: <?php echo $row['publication_date'];?></p>
				<p><?php echo $row['price'];?></p>
				<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
				<input type="hidden" id="add_to_basket" name="add_to_basket" value="<?php echo $id;?>">
				<p><button class="button">Add To Bag</button></p>
				</form>
            </div>
        </div>
    </div>
</div>
<hr>
<?php show_footer();?>
</body>
</html>

<?php include 'subroutines.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>BookStore User</title>
    <link rel="stylesheet" type="text/css" href="homepage.css">
</head>

<body>
<?php show_sidenav(); show_topbar(); show_modal();?>

<div class="main">
    <h1 style="text-align: center; font-size: 2vw";> Your Basket</h1>
    <hr width="50%">
</div>

<table class="main" style="width:85%; margin-left: 12%;">
    <tr>
        <th colspan="2">Items</th>
        <th>Quantity</th>
        <th>Price</th>
    </tr>

    <?php
    $db = mysql_connect();
    $query = 'SELECT id, title, author, price, pictures FROM products WHERE active=1 ORDER BY title ASC;';
    $result = mysqli_query($db, $query);
    $products = [];

    while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) !== NULL) {
        foreach ($row as $key => $value) {
            $row[$key] = trim(stripslashes($value));
        }
        $products[$row['id']] = $row;
    }
    mysqli_free_result($result);
    mysqli_close($db);

    if (isset($_SESSION["basket_items"])) {
        $ids = explode(",", $_SESSION["basket_items"]);
        $total = 0;
        foreach ($ids as $cnt) {
            echo '<tr>' . "\n";
            echo '<td style="text-align: left";><img src="' . $products[$cnt]['pictures'] . '" style="width: 40%;" </td>' . "\n"; //add image from id of table
            echo '<td style="text-align: left";>' . $products[$cnt]['title'] . '</td>' . "\n"; //
            echo '<td style="text-align: center";>1</td>' . "\n";
            echo '<td style="text-align: center";>' . $products[$cnt]['price'] . '</td>' . "\n";
            echo '</tr>' . "\n";
            $price = str_replace('$', '', $products[$cnt]['price']);
            $total = $total + $price;
        }
        echo '<tr>' . "\n";
        echo '<td colspan="2"></td>' . "\n";
        echo '<th>Total: </th>' . "\n";
        echo '<td style="text-align: center";>$' . $total . '</td>' . "\n";
        echo '</tr>' . "\n";
    } else {
        echo '<tr>' . "\n";
        echo '<td colspan="4" style="text-align: center";>Your cart is empty.</td>' . "\n";
        echo '</tr>' . "\n";
    }
?>

    <tr>
        <td colspan="1" style="text-align: left"><a href="admin_categories.php" style="height: 64px"><button class="button" style="width: 40%; color: greenyellow">Admin Pages</button></a></td></td>
        <td><form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
            <input type="hidden" id="clear_basket" name="clear_basket" value="1">
        <input type="submit" value="clear basket" class="button" style="width: 40%;" >
        </form></td>
        <td colspan="2" style="text-align: center";><button class="button" style="width: 100%">Check Out</button></td>
    </tr>

</table>
<hr>
<?php show_footer(); ?>
</body>
</html>

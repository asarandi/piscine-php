<?php
session_start();
include_once 'minishop.php';
include_once 'navbar.php';

function basket_view(){
    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = [];
    }
    $total_cost = 0;
    foreach ($_SESSION['basket'] as $product_id => $count) {
        $product = get_sql_data_for_table_id('products', $product_id);
        if ($product) {
            $price = $product['price'];
            $total_cost += $price * $count;
            echo sprintf('<p>%d x %s @ $%d.%d</p>', $count, $product['title'], $price / 100, $price % 100);
        }
    }
    echo sprintf('<br /><h4>total cost $%d.%d</h4><hr>', $total_cost / 100, $total_cost % 100);

    echo '<a href="basket.php?action=save">save</a>      ';
    echo '<a href="basket.php?action=restore">restore</a>      ';
    echo '<a href="basket.php?action=clear">clear</a>     ';
}

if (isset($_GET['action'])){
    if ($_GET['action'] === 'add') {
        if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
            $id = $_GET['product_id'];
            if (!isset($_SESSION['basket'])) {
                $_SESSION['basket'] = [];
            }
            if (!isset($_SESSION['basket'][$id])) {
                $_SESSION['basket'][$id] = 0;
            }
            $_SESSION['basket'][$id] += 1;
            echo "<h3>product added to basket</h3>";
            header('refresh:1;url='.$_SERVER['HTTP_REFERER']);
        }
    }
    elseif ($_GET['action'] === 'view') {
        basket_view();
    }
    elseif ($_GET['action'] === 'save') {
        if (!is_user_logged_in()) {
            echo "<h3>you must be logged in to save your basket</h3>\n";
            header('refresh:2;url=login.php');
        } else {
            if (save_user_basket() === TRUE) {
                echo "<h3>basket saved successfully</h3>\n";
                header('refresh:2;url=basket.php?action=view');
            } else {
                echo "<h3>failed to save basket</h3>\n";
                header('refresh:2;url=basket.php?action=view');
            }
        }
    }
    elseif ($_GET['action'] === 'restore') {
        if (!is_user_logged_in()) {
            echo "<h3>you must be logged in to restore your basket</h3>\n";
            header('refresh:2;url=login.php');
        } else {
            if (restore_user_basket() === TRUE) {
                echo "<h3>basket restored successfully</h3>\n";
                header('refresh:2;url=basket.php?action=view');
            } else {
                echo "<h3>failed to restore basket</h3>\n";
                header('refresh:2;url=basket.php?action=view');
            }
        }
    } 
    
    elseif ($_GET['action'] === 'clear') {
        $_SESSION['basket'] = [];
        echo "<h3>your basket is now clear</h3>\n";
        header('refresh:2;url=basket.php?action=view');
    }       
    
    else {
        basket_view();
    }
} else {
    basket_view();
}
?>
</body>
</html>

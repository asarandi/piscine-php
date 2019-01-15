<?php

$GLOBALS['SQL_HOST']       = '127.0.0.1';
$GLOBALS['SQL_USER']       = 'root';
$GLOBALS['SQL_PASSWORD']   = 'root';
$GLOBALS['SQL_DATABASE']   = 'php_rush00';
$GLOBALS['SQL_PORT']       = 8889;

function db_connect() {
    $host = $GLOBALS['SQL_HOST'];
    $user = $GLOBALS['SQL_USER'];
    $password = $GLOBALS['SQL_PASSWORD'];
    $database = $GLOBALS['SQL_DATABASE'];
    $port = $GLOBALS['SQL_PORT'];
    $link = mysqli_connect($host, $user, $password, $database, $port);
    return $link;
}

function get_sql_data_for_table_id($table, $id) {
    $link = db_connect();

    if (!$link) { 
        die("mysqli connect failed");
    }

    $id = mysqli_real_escape_string($link, $id);
    if (!is_numeric($id)) {
        mysqli_close($link);
        die("id must be numeric\n");
    }

    $table = mysqli_real_escape_string($link, $table);
    
    $query = mysqli_query($link, 'SELECT * FROM '.$table.' WHERE active=1 AND id='.$id.';');
    if (!$query) {
        mysqli_close($link);
        die("mysqli query failed");
    }

    $result = mysqli_fetch_array($query, MYSQLI_ASSOC);
    mysqli_close($link);
    if (!$result) {
        die("mysqli query failed");
    }

    return $result;
}

function make_html_product_card($product_id) {
    $PRODUCT_IMAGE_STYLE = 'width="200" height="200"';
    $product = get_sql_data_for_table_id('products', $product_id);
    if (!product) {
        die("failed to get sql data for product id\n");        
    }
    $f  = sprintf('<h2>%s</h2>', $product['title']);
    $f .= sprintf('<a href="basket.php?action=add&product_id=%d">', $product['id']);
    $f .= sprintf('<img src="%s" alt="%s image" %s></a>', $product['photo'], $product['title'], $PRODUCT_IMAGE_STYLE);
    $price = (int)$product['price'];
    $f .= sprintf('<h4>$%d.%d</h4>', $price / 100, $price % 100);
    $f .= sprintf('<h5>%s</h5><hr>', $product['description']);
    return $f;
}

function csv_to_array($file){
    $csv = array_map('str_getcsv', file($file));
    array_walk($csv, function(&$a) use ($csv) {
      $a = array_combine($csv[0], $a);
    });
    array_shift($csv);
    return $csv;
}

function    make_html_editor_links() {
    if (!$link = db_connect()) {
        die("failed to connect to db\n");
    }

    if (!($query = mysqli_query($link, 'SHOW TABLES;'))) {
        mysqli_close($link);
        die("show tables query failed\n");
    }

    $tables = [];
    while ($row = mysqli_fetch_array($query, MYSQLI_NUM)) {
        $tables[] = $row[0];
    }

    mysqli_close($link);

    $result = '';
    $EDITOR_SCRIPT_NAME = 'editor.php';
    foreach ($tables as $table) {
        $result .= sprintf('<a href="%s?table=%s" style="color: blue; text-decoration: none;">%s</a>         ', $EDITOR_SCRIPT_NAME, $table, $table);
    }
    return $result;
}


function does_login_exist($login) {
    $link = db_connect();
    if (!$link) { 
        die("mysqli connect failed");
    }
    $login = mysqli_real_escape_string($link, $login);    
    $query = mysqli_query($link, 'SELECT login FROM users WHERE active=1 AND login="'.$login.'";');
    if (!$query) {
        mysqli_close($link);
        return FALSE;
    }
    $result = mysqli_fetch_array($query, MYSQLI_ASSOC);
    mysqli_close($link);
    if (!$result) {
        return FALSE;
    }
    if ($result['login']) {
        return TRUE;
    }
    return FALSE;
}

function create_new_user($login, $passwd) {
    $link = db_connect();
    if (!$link) { 
        die("mysqli connect failed");
    }
    $login = mysqli_real_escape_string($link, $login);
    $passwd = hash('whirlpool', $passwd);
    $values = '"'.$login.'", "'.$passwd.'", 0, 1';
    $query = mysqli_query($link, 'INSERT INTO users (login, passwd, admin, active) VALUES ('.$values.');');
    mysqli_close($link);

    if (!$query) {
        return FALSE;
    }
    return TRUE;
}

function is_valid_login_passwd($login, $passwd) {
    $link = db_connect();
    if (!$link) { 
        die("mysqli connect failed");
    }

    $login = mysqli_real_escape_string($link, $login);    
    $query = mysqli_query($link, 'SELECT passwd FROM users WHERE active=1 AND login="'.$login.'";');
    if (!$query) {
        mysqli_close($link);
        return FALSE;
    }

    $result = mysqli_fetch_array($query, MYSQLI_ASSOC);
    mysqli_close($link);
    if (!$result) {
        return FALSE;
    }

    if ($result['passwd'] === hash('whirlpool', $passwd)) {
        return TRUE;
    }
    return FALSE;
}

function is_admin_user() {
    if (!isset($_SESSION['logged_on_user'])) {
        return FALSE ;
    }
    $login = $_SESSION['logged_on_user'];
    $link = db_connect();
    if (!$link) { 
        die("mysqli connect failed");
    }

    $login = mysqli_real_escape_string($link, $login);    
    $query = mysqli_query($link, 'SELECT admin FROM users WHERE active=1 AND login="'.$login.'";');
    if (!$query) {
        mysqli_close($link);
        return FALSE;
    }

    $result = mysqli_fetch_array($query, MYSQLI_ASSOC);
    mysqli_close($link);
    if (!$result) {
        return FALSE;
    }

    if ($result['admin'] === '1') {
        return TRUE;
    }
    return FALSE;
}

function is_user_logged_in() {
    if (!isset($_SESSION['logged_on_user'])) {
        return FALSE ;
    }
    if (strlen($_SESSION['logged_on_user']) < 1) {
        return FALSE ;
    }

    $login = $_SESSION['logged_on_user'];
    $link = db_connect();
    if (!$link) { 
        die("mysqli connect failed");
    }

    $login = mysqli_real_escape_string($link, $login);    
    $query = mysqli_query($link, 'SELECT active FROM users WHERE login="'.$login.'";');
    if (!$query) {
        mysqli_close($link);
        return FALSE;
    }

    $result = mysqli_fetch_array($query, MYSQLI_ASSOC);
    mysqli_close($link);
    if (!$result) {
        return FALSE;
    }

    if ($result['active'] === '1') {
        return TRUE;
    }
    return FALSE;
}

function get_current_user_id() {
    if (!isset($_SESSION['logged_on_user'])) {
        return -1 ;
    }
    if (strlen($_SESSION['logged_on_user']) < 1) {
        return -1 ;
    }
    $login = $_SESSION['logged_on_user'];
    $link = db_connect();
    if (!$link) { 
        die("mysqli connect failed");
    }
    $login = mysqli_real_escape_string($link, $login);    
    $query = mysqli_query($link, 'SELECT id FROM users WHERE active=1 AND login="'.$login.'";');
    if (!$query) {
        mysqli_close($link);
        return -1;
    }

    $result = mysqli_fetch_array($query, MYSQLI_ASSOC);
    mysqli_close($link);
    if (!$result) {
        return -1;
    }
    return (int)$result['id'];
}

function clear_user_basket() {
    if (($id = get_current_user_id()) == -1) {
        return FALSE;
    }

    $link = db_connect();
    if (!$link) { 
        die("mysqli connect failed");
    }
    $query = mysqli_query($link, 'UPDATE baskets SET active=0 WHERE user_id='.$id.';');
    mysqli_close($link);
    if (!$query) {
        return FALSE;
    }
    return TRUE;
}

function get_users_active_basket_id($user_id) {
    $link = db_connect();
    if (!$link) { 
        die("mysqli connect failed");
    }
    $login = mysqli_real_escape_string($link, $login);    
    $query = mysqli_query($link, 'SELECT id FROM baskets WHERE active=1 AND user_id="'.$user_id.'";');
    if (!$query) {
        mysqli_close($link);
        return -1;
    }

    $result = mysqli_fetch_array($query, MYSQLI_ASSOC);
    mysqli_close($link);
    if (!$result) {
        return -1;
    }
    return (int)$result['id'];
}

function save_user_basket() {
    if (!clear_user_basket()) {
        return FALSE;
    }
    if (($user_id = get_current_user_id()) == -1) {
        return FALSE;
    }
    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = [];
    }
    $link = db_connect();
    if (!$link) { 
        die("mysqli connect failed");
    }
    $time = time();
    $values = "$user_id, $time, 1";    
    $query = mysqli_query($link, 'INSERT INTO baskets (user_id,time_created,active) VALUES ('.$values.');');
    if (!$query) {
        mysqli_close($link);
        return FALSE;
    }

    $basket_id = get_users_active_basket_id($user_id);
    if ($basket_id == -1) {
        return FALSE;
    }
    foreach ($_SESSION['basket'] as $product_id => $product_qty) {
        $values = "$basket_id, $product_id, $product_qty, 1";
        $query = mysqli_query($link, 'INSERT INTO basket_items (basket_id,product_id,product_qty,active) VALUES ('.$values.');');
        if (!$query) {
            mysqli_close($link);
            return FALSE;
        }
    }
    mysqli_close($link);
    return TRUE;
}

function restore_user_basket() {
    if (($user_id = get_current_user_id()) == -1) {
        return FALSE;
    }
    $link = db_connect();
    if (!$link) { 
        die("mysqli connect failed");
    }
    $basket_id = get_users_active_basket_id($user_id);
    if ($basket_id == -1) {
        return FALSE;
    }
    $query = mysqli_query($link, "SELECT product_id, product_qty FROM basket_items WHERE basket_id=$basket_id AND active=1;");
    if (!$query) {
        mysqli_close($link);
        return FALSE;
    }
    $_SESSION['basket'] = [];
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $product_id = $row['product_id'];
        $_SESSION['basket'][$product_id] = $row['product_qty'];
    }
    mysqli_close($link);
    return TRUE;
}


?>

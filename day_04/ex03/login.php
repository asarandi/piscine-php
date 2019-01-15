<?php
session_start();
include 'auth.php';
$result = "ERROR\n";
if (isset($_GET['login']) && isset($_GET['passwd'])){
    if (auth($_GET['login'], $_GET['passwd']) === TRUE) {
        $_SESSION['logged_on_user'] = $_GET['login'];
        $result = "OK\n";
    }
}
echo $result;
?>

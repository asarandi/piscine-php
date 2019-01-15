<?php
session_start();
$result = "ERROR\n";
if (isset($_SESSION['logged_on_user']) && strlen($_SESSION['logged_on_user']) > 0) {
    $result = $_SESSION['logged_on_user'] . "\n";
}
echo $result; 
?>

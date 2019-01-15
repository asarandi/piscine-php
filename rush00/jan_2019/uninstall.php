<?php
include_once 'minishop.php';
$host = $GLOBALS['SQL_HOST'];
$user = $GLOBALS['SQL_USER'];
$password = $GLOBALS['SQL_PASSWORD'];
$database = $GLOBALS['SQL_DATABASE'];
$port = $GLOBALS['SQL_PORT'];
if (!($link = mysqli_connect($host, $user, $password, NULL, $port))) {
    die("failed to connect to mysql server\n");
}
if (!mysqli_query($link, "DROP DATABASE IF EXISTS $database;")) {
    echo("drop database query failed\n");
}
mysqli_close($link);
echo "database destroyed successfully\n";
?>

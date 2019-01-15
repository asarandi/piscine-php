<?php
session_start();
include 'minishop.php';
include 'navbar.php';   //contains html, head, body
$result = "session destroyed";
if (isset($_SESSION['logged_on_user']) && strlen($_SESSION['logged_on_user']) > 0){
    $_SESSION['logged_on_user'] = '';
    $result .= " and you are now logged out";
}
session_destroy();
echo "<h3>$result</h3>";
header('refresh:2;url=/');
?>
</body>
</html>

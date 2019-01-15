<?php
session_start();
include 'auth.php';
$result = FALSE;
if (isset($_POST['login']) && isset($_POST['passwd'])){
    if (auth($_POST['login'], $_POST['passwd']) === TRUE) {
        $_SESSION['logged_on_user'] = $_POST['login'];
        $result = TRUE;
    }
}
if ($result !== TRUE) {
    echo "ERROR\n";
    header('refresh:2;url=index.html');
    exit ;
}
?>
<html>
<body>
<iframe name="chat" src="chat.php" width="100%" height="550px"></iframe>
<iframe name="speak" src="speak.php" width="100%" height="50px"></iframe>
<a href="logout.php">logout</a>
</body>
</html>

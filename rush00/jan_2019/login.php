<?php
session_start();
include 'navbar.php';   //contans html, head, body
include 'minishop.php';
$result = "invalid login/password";
$url = $_SERVER['HTTP_REFERER'];
if (isset($_POST['login']) && isset($_POST['passwd'])){
    if (is_valid_login_passwd($_POST['login'], $_POST['passwd']) === TRUE) {
        $link = db_connect();
        if (!$link) { die("mysqli connect failed"); }
        $login = mysqli_real_escape_string($link, $_POST['login']);    
        mysqli_close($link);
        $_SESSION['logged_on_user'] = $login;
        $result = "success";
        $url = '/';
    }
    echo "<h3>$result</h3>\n";
    header('refresh:2;url='.$url);
    exit ;
}
?>
<br />
<form action="login.php" method="POST">
Username: <input type="text" name="login">
<br />
Password: <input type="password" name="passwd">
<input type="submit" name="submit" value="OK">
</form>
<a href="create.php">create an account</a>
<br />
</body>
</html>

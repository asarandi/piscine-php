<?php
session_start();
include_once 'minishop.php';
include_once 'navbar.php';
if (isset($_POST['submit']) && isset($_POST['login']) && isset($_POST['passwd'])) {
    if (($_POST['submit'] === 'OK') && strlen($_POST['login']) > 0 && strlen($_POST['passwd']) > 0) {
        if (does_login_exist($_POST['login']) === TRUE) {
            $result = "user already exists";
        } else {
            //create user
            if (create_new_user($_POST['login'], $_POST['passwd']) === TRUE){
                $result = "user created successfully";
            } else {
                $result = "failed to create user";
            }

        }
    }
    else {
        $result = "invalid post fields";
    }
    echo "<h3>$result</h3>";
    header('refresh:2;url=login.php');
    exit ; 
}
?>
<br />
<form action="create.php" method="POST">
Username:
<input type="text" name="login">
<br />
Password:
<input type="password" name="passwd">
<input type="submit" name="submit" value="OK">
</form>
</body>
</html>

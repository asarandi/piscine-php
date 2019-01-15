#!/usr/bin/php
<?php

function check_input($login, $passwd, $passwd2, $submit)
{
    if (!$login || strlen($login) < 1 || strlen($login) > 20 || !ctype_print($login))
    {
        echo "Login must be of 1 to 20 printable characters\n";
        exit();
    }
    if (!$passwd || strlen($passwd) < 8 || strlen($passwd) > 30 || !ctype_print($passwd) || $passwd !== $passwd2)
    {
        echo "Password must be of 8 to 30 printable characters and match the confirm password field\n";
        exit();
    }
    if (!$submit || $submit !== "OK")
    {
        echo "Unexpected error, if you are a hacker, please get out\n";
        exit();
    }
}
check_input($_POST['login'], $_POST['passwd'], $_POST['passwd2'], $_POST['submit']);
$users = get_db_to_array("users");
if ($users)
{
        foreach ($users as $key => $value)
        {
                if ($value['login'] === $_POST['login'])
                {
                        echo "Sorry, this username is already taken\n";
                        exit();
                }
        }
}
$users[] = array('login' => $_POST['login'], 'passwd' => hash('whirlpool', $_POST['passwd']), 'admin' => 0, 'active' => 0);
//file_put_contents('../private/passwd', serialize($users));
echo "Your account has successfully been created, you can now log in [=\n";
?>
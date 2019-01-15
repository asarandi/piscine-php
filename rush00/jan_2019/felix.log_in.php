#!/usr/bin/php
<?php

//    if (!($users = unserialize(file_get_contents("../private/passwd"))))  <- need this
//       return FALSE;

function check_input($login, $passwd, $submit)
{
    if (!$login || strlen($login) < 1 || strlen($login) > 20 || !ctype_print($login))
    {
        echo "Login must be of 1 to 20 printable characters\n";
        exit();
    }
    if (!$passwd || strlen($passwd) < 8 || strlen($passwd) > 30 || !ctype_print($passwd))
    {
        echo "Password must be of 8 to 30 printable characters\n";
        exit();
    }
    if (!$submit || $submit !== "OK")
    {
        echo "Unexpected error, if you are a hacker, please get out\n";
        exit();
    }
}

if (!$GLOBALS['wrong_passwd'])
    $GlOBALS['wrong_passwd'] = 0;
else if ($GLOBALS['wrong_passwd'] > 5)
    sleep(300);
check_input($_POST['login'], $_POST['passwd'], $_POST['submit']);
foreach ($users as $key => $value)
{
    if ($value['login'] === $_POST['login'])
    {
        if ($value['passwd'] === hash('whirlpool', $_POST['passwd']))
        {
            echo ("Welcome ".$value['login']."\n");
            $value['active'] = 1;
//  Maybe something that opens another page or something
            exit();
        }
        else
        {
            echo "Wrong password\n";
            $GLOBALS['wrong_passwd']++;
            exit();
        }
    }
}
echo "This username does not exist\n";
?>
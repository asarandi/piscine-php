#!/usr/bin/php
<?php

function delete_acc($login)
{
    foreach ($users as $key => $value)
    {
        if ($value['login'] === $_SESSION['login'])
            unset($users[$key]);
    }
}


?>

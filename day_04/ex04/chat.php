<?php
session_start();
date_default_timezone_set('America/Los_Angeles');
if (isset($_SESSION['logged_on_user']) && strlen($_SESSION['logged_on_user']) > 0) {
    $CHAT_FILE = '/tmp/private/chat';
    if (($data = unserialize(file_get_contents($CHAT_FILE))) !== FALSE) {
        foreach ($data as $msg) {
            $when = date("H:i:s", $msg['time']);
            printf("[%s] <b>%s</b>: %s<br />\n", $when, $msg['login'], $msg['msg']);      
        }
    }
}
?>

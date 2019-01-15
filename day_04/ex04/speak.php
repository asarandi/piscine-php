<?php
session_start();
if (isset($_POST['msg']) && strlen($_POST['msg']) > 0) {
    if (isset($_SESSION['logged_on_user']) && strlen($_SESSION['logged_on_user']) > 0) {
        $CHAT_FILE = '/tmp/private/chat';
        $new_msg = [];
        $new_msg['login'] = $_SESSION['logged_on_user'];
        $new_msg['time'] = time();
        $new_msg['msg'] = $_POST['msg'];
        if (($fd = fopen($CHAT_FILE, 'c+')) !== FALSE) {
            if (flock($fd, LOCK_EX) === TRUE) {
                $array = [];
                if (($data = unserialize(file_get_contents($CHAT_FILE))) !== FALSE) {
                    $array = $data;
                }
                $array[] = $new_msg;
                file_put_contents($CHAT_FILE, serialize($array));
            }
            fclose($fd);
        }
    }
}
?>
<html>
<head>
<script language="javascript">top.frames['chat'].location = 'chat.php';</script>
</head>
<body>
<form action="speak.php" method="POST">
<input type="text" name="msg">
<input type="submit" name="submit" value="OK">
</form>
</body>
</html>

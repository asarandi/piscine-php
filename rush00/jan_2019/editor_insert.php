<?php
session_start();
include_once 'minishop.php';
include_once 'navbar.php';
if (!is_admin_user()) {
    echo "<h3>unauthorized</h3>\n";
    header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
    exit;
}

if (!$link = db_connect()) {
    die("failed to connect to db\n");
}

if (isset($_POST)) {
    if (isset($_POST['submit']) && $_POST['submit'] === 'insert') {
        if (isset($_POST['table']) && strlen($_POST['table']) > 0) {
            $array = $_POST;
            $table = mysqli_real_escape_string($link, $array['table']);
            if (isset($array['id'])) {
                unset($array['id']);
            }
            unset($array['table']);
            unset($array['submit']);
            $keys = array_keys($array);
            foreach ($keys as &$key) {
                $key = mysqli_real_escape_string($link, $key);
            }
            $values = array_values($array);
            foreach ($values as &$value) {
                $value = mysqli_real_escape_string($link, $value);
            }

            $query = sprintf(
                'INSERT INTO %s (%s) VALUES ("%s")',
                $table,
                implode(',', $keys),
                implode('","', $values)
            );

            $res = mysqli_query($link, $query);
            mysqli_close($link);
            if (!res) {
                echo "mysqli insert query failed\n";
            } else {
                echo "ok";
            }
            header('refresh:1;url='.$_SERVER['HTTP_REFERER']);
        }
    }
}
?>

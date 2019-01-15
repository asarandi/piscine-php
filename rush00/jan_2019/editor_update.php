<?php
session_start();
/*
 * DONE: make sure user is authenticated via $_SESSION and has admin privileges
 */

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
    if (isset($_POST['submit']) && $_POST['submit'] === 'update') {
        if (isset($_POST['table']) && strlen($_POST['table']) > 0) {
            if (isset($_POST['id']) && strlen($_POST['id']) > 0) {

                /*
                 * the logic is to remove 'id', 'table' and 'submit' from $_POST array
                 * the remaining elements should be key => value pairs for SQL UPDATE statement
                 * 'id' will be used to find the corresponding row
                 */

                $array = $_POST;
                $table = mysqli_real_escape_string($link, $array['table']);
                $id = mysqli_real_escape_string($link, $array['id']);
                unset($array['table']);
                unset($array['id']);
                unset($array['submit']);
                $keys = array_keys($array);
                foreach ($keys as &$key) {
                    $key = mysqli_real_escape_string($link, $key);
                }
                $values = array_values($array);
                foreach ($values as &$value) {
                    $value = mysqli_real_escape_string($link, $value);
                }
                $array = array_combine($keys, $values);
                $query = "UPDATE $table SET ";
                foreach ($array as $k => $v) {
                    $query .= $k.'="'.$v.'", ';
                }
                $query = substr($query, 0, -2);
                $query .= " WHERE id=$id;";

                $res = mysqli_query($link, $query);
                mysqli_close($link);
                if (!res) {
                    echo "mysqli update query failed\n";
                } else {
                    echo "ok";
                }
                header('refresh:1;url='.$_SERVER['HTTP_REFERER']);
            }
        }
    }
}
?>

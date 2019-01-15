<?php

/*
 *
 * logic for create.php
 *
 * returns 'OK' only if its able to insert a new login/password into private/passwd file
 * return 'ERROR' if:
 *      -$_POST is missing fields and/or fields are not valid
 *      -it can't create/open/read/deserialize directory/file,
 *      -login field already exists
 *
 */


$result = "ERROR\n";

$PWD_DIR = '/tmp/private';
$PWD_FILE = $PWD_DIR . '/passwd';

if (isset($_POST['submit']) && isset($_POST['login']) && isset($_POST['passwd'])) {
    if (($_POST['submit'] === 'OK') && strlen($_POST['login']) > 0 && strlen($_POST['passwd']) > 0) {
        if (file_exists($PWD_FILE) !== TRUE) {                         //if there's no private/passwd
            mkdir($PWD_DIR);                                               //create dir
            file_put_contents($PWD_FILE, serialize(array()));        //create a file with a serialized empty array
        }
        if (($data = unserialize(file_get_contents($PWD_FILE))) !== FALSE) {
            $new_user = TRUE;
            foreach ($data as $user) {
                if ($user['login'] === $_POST['login']) {
                    $new_user = FALSE;
                    break ;
                }

            }
            if ($new_user === TRUE) {
                $new_user = [];
                $new_user['login'] = $_POST['login'];
                $new_user['passwd'] = hash('whirlpool', $_POST['passwd']);
                $data[] = $new_user;
                if ((file_put_contents($PWD_FILE, serialize($data))) !== FALSE) {
                    $result = "OK\n";
                }
            }
        }
    }   
}

echo $result;

?>

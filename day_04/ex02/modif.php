<?php

/*
 *
 * logic for modif.php
 *
 * returns 'OK' only if it's able to update an existing password for an existing login
 * return 'ERROR' if:
 *      -$_POST is missing fields and/or fields are not valid
 *      -it can't create/open/read/deserialize directory/file,
 *      -login field does not exist
 *      -oldpw does not matching existing pw in database
 *      -newpw is blank
 *
 */


$result = "ERROR\n";
$PWD_FILE = '/tmp/private/passwd';       /* XXX adjust path as necessary */

$required_fields = ['submit', 'login', 'oldpw', 'newpw'];
$have_all_fields = TRUE;

foreach ($required_fields as $field) {
    $field_valid = FALSE;
    if (isset($_POST[$field]) && strlen($_POST[$field]) > 0) {
        $field_valid = TRUE;
    }
    if ($field_valid !== TRUE) {
        $have_all_fields = FALSE;
    }
}

if ($have_all_fields === TRUE && $_POST['submit'] === 'OK') {
    if (($data = unserialize(file_get_contents($PWD_FILE))) !== FALSE) {
        foreach ($data as &$user) {
            if ($user['login'] === $_POST['login']) {
                if ($user['passwd'] === hash('whirlpool', $_POST['oldpw'])) {
                    $user['passwd'] = hash('whirlpool', $_POST['newpw']);
                    if (file_put_contents($PWD_FILE, serialize($data)) !== FALSE) {
                        $result = "OK\n";
                    }
                }
                break ;
            }
        }
    }   
}

echo $result;

?>

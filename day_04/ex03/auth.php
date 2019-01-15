<?php
function auth($login, $passwd) {
    $PWD_FILE = '/tmp/private/passwd';
    if (($data = unserialize(file_get_contents($PWD_FILE))) !== FALSE) {
        foreach ($data as $user) {
            if ($user['login'] === $login) {
                if ($user['passwd'] === hash('whirlpool', $passwd)) {
                    return TRUE;
                }
            }
        }
    }
    return FALSE;
}
?>

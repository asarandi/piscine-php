<?php
if (isset($_POST)) {
    if (($fp = fopen('list.csv', 'w')) === FALSE) {
        exit('error: fopen() failed');
    }
    foreach ($_POST as $k => $v) {
        if ((fputcsv($fp, array($k, $v), ';')) === FALSE) {
            fclose($fp);
            exit('error: fputcsv() failed');
        }
    }
    fclose($fp);
   exit('ok');
} else {
    exit('exrror: expecting http post request');
}
?>

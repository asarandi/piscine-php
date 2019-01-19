<?php
if (($fp = fopen('list.csv', 'r')) === FALSE) {
    exit(json_encode(array()));
}
$todo = [];
while (($row = fgetcsv($fp, 4096, ';')) !== FALSE) {
    $todo[] = $row[1];
}
fclose($fp);
exit(json_encode(array_reverse($todo)));
?>

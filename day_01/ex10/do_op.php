#!/usr/bin/php
<?php
if ($argc == 4) {

	$a = intval(trim($argv[1]));
	$op = trim($argv[2]);
	$b = intval(trim($argv[3]));

	$res = 0;
	if (strcmp($op, "+") == 0) {
		$res = ($a + $b);
	} else if (strcmp($op, "-") == 0) {
		$res = $a - $b;
	} else if (strcmp($op, "*") == 0) {
		$res = $a * $b;
	} else if (strcmp($op, "/") == 0) {
		$res = $a / $b;
	} else if (strcmp($op, "%") == 0) {
		$res = $a % $b;
	} else {
		exit("Incorrect Parameters\n");
	}
	echo $res . "\n";

} else {
	echo "Incorrect Parameters\n";
}
?>

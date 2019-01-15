#!/usr/bin/php
<?php
if ($argc == 2) {

	$str = preg_replace('/\s+/', '', $argv[1]);
	$operators = array('+', '-', '*', '/', '%');
	$op = '';
	foreach ($operators as $operator)
	{
		if (strpos($str, $operator) !== false) {
			$op = $operator;
			break ;
		}
	}
	if ($op === '') {
		exit("Syntax Error\n");
	}

	$split = explode($op, $str);
	if ((!is_numeric($split[0])) || (!is_numeric($split[1]))){
		exit("Syntax Error\n");
	}

	$a = intval($split[0]);
	$b = intval($split[1]);

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

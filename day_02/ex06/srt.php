#!/usr/bin/php
<?php
if ($argc == 2) {
	if (($fp = fopen($argv[1], 'r')) === false)
		exit("Failed to open file: " . $argv[1] . "\n");

	$timestamp = [];
	$messages = [];

/*

	this while loop will work if the input file is structured properly;
	the expected file format is:
		line#1 = msg number;
		line#2 = timestamp;
		line#3 = message;
		line#4 = blank line;

	we could also try to read and then sort input into appropriate array:
		for ex:			if 		- case1: grep for ' --> '  ... if found, push into $timestamps[] array
						else if - case2: if $line is all numeric ... then push into $key[] array; this could fail;
						else if - case3: not case 1, not case 2 AND strlen > 0, push into $messages[] array;
						else	- case4: must be empty line; discard

*/

	while (true)
	{
		if (($str = fgets($fp)) === false) break ;
		$key = trim($str);
		if (($str = fgets($fp)) === false) break ;
		$timestamps[trim($str)] = $key;
		if (($str = fgets($fp)) === false) break ;
		$messages[$key] = trim($str);
		$str = fgets($fp);	//should be a blank line
	}
	fclose($fp);

	ksort($timestamps);

	$i = 1;
	foreach ($timestamps as $timestamp => $key) {
		echo "$i\n"; $i++;
		echo "$timestamp\n";
		echo $messages[$key] . "\n\n";
	}
}
?>

#!/usr/bin/php
<?php
/*

e1z4r13p9% who
asarandi console  Oct  4 15:51
asarandi ttys000  Oct  4 15:51
asarandi ttys001  Oct  4 15:51
asarandi ttys002  Oct  4 15:51
e1z4r13p9%

*/

date_default_timezone_set("America/Los_Angeles");

if ($argc > 1) {
	$file = $argv[1];
} else {
	$file = '/var/run/utmpx';
}

if (($fp = fopen($file, 'r')) === false) {
	exit("Failed to open file: $file\n");
}

$fs = filesize($file);

if (($data = fread($fp, $fs)) === false) {
	exit("Failed to read file: $file\n");
}
fclose($fp);


/*
 * format description /var/run/utmpx
 * at offset 0: 							string 'utmpx-1.00'
 * at offset 0x4e8							username
 * at offset 0x4e8 + 0x104					terminal name
 * at offset 0x4e8 + 0x104 + 0x28			timestamp
 * at offset 0x4e8 + 0x104 + 0x28 + 0x148	... another username
 *
 * size of record is 0x104 + 0x28 + 0x148 ....  == 0x274 bytes
 * records start at 0x4e8
 *
*/

$header = 'utmpx-1.00';
if (strncmp($data, $header, strlen($header)) !== 0) {
	exit("Wrong file format: $file\n");
}

	$ptr = 0x4e8;

$strings = [];


while ($ptr < $fs) {
	$str = '';

	$i = $ptr;
	while (ord($data[$i]) != 0)			//print user name
		$str .= $data[$i++];
	$str .= ' ';
	$ptr += 0x104;

	$i = $ptr;
	while (ord($data[$i]) != 0)			//print terminal name
		$str .= $data[$i++];
	$str .= '  ';

	$ptr += 0x28;
	$timestamp = ord($data[$ptr + 3]) * 0x1000000;
	$timestamp += ord($data[$ptr + 2]) * 0x10000;
	$timestamp += ord($data[$ptr + 1]) * 0x100;
	$timestamp += ord($data[$ptr]);

	$str .= date("M  j H:i", $timestamp);
	$strings[] = $str;

	$ptr += 0x148;
}
	sort($strings);
	foreach ($strings as $string)
		echo "$string\n";

?>

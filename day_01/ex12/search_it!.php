#!/usr/bin/php
<?php
if ($argc > 2) {
	$key = trim($argv[1]);
	$kv = [];
	for ($i = 2; $i < $argc; $i++) {
		$str = trim($argv[$i]);
		$split = explode(":", $str);
		$kv[$split[0]] = $split[1];
	}
	if (isset($kv[$key])) {
		echo $kv[$key] . "\n";
	}
}
?>

#!/usr/bin/php
<?php
if ($argc > 1) {
	$str = "";
	for ($i = 1; $i < $argc; $i++) {
		$str .= " " . $argv[$i];
	}
	$split = explode(" ", $str);
	sort($split);
	foreach ($split as $word) {
		if (strlen($word) > 0) {
			echo "$word\n";
		}
	}
}
?>

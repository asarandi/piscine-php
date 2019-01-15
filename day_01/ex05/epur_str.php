#!/usr/bin/php
<?php
if ($argc == 2) {
	$arr = explode(" ", $argv[1]);
	$res = "";
	for ($i=0; $i < count($arr); $i++) {
		if (strlen($arr[$i]) > 0) {
			if (strlen($res) > 0) {
				$res .= " ";
			}
			$res .= $arr[$i];
		}
	}
	echo "$res\n";
}
?>

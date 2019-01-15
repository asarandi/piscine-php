#!/usr/bin/php
<?php
if ($argc > 1) {
	$str = preg_replace('/\s+/', ' ', $argv[1]);	//replace all matches with a single space
	echo trim($str) . "\n";						//trim beginning and end of $str; print with a new line
	}
?>

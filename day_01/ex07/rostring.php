#!/usr/bin/php
<?php
if ($argc > 1)
{
	$split = explode(" ", $argv[1]);
	$res = [];
	foreach ($split as $word){
		if (strlen($word) > 0) {
			$res[] = $word;
		}
	}
	if (count($res) > 0)
	{
		$tail = $res[0];
		unset($res[0]);
		$res[] = $tail;
		echo implode(" ", $res) . "\n";
	}
}
?>

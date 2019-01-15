#!/usr/bin/php
<?php

function banana_sort($a, $b)
{
	if ((strlen($a) < 1) && (strlen($b) > 0)) {
		return (1);
	}
	else if ((strlen($b) < 1) && (strlen($a) > 0)) {
		return (-1);
	}
	else if ((strlen($a) < 1) && (strlen($b) < 1)) {
		return (0);
	}
	$i = 0;

	while (true)
	{
		if (!isset($a[$i])) {
			$x = chr(0);
		} else {
			$x = strtolower($a[$i]);
		}

		if (!isset($b[$i])) {
			$y = chr(0);
		} else {
			$y = strtolower($b[$i]);
		}

		if ((ord($x) == 0) || (ord($y) == 0))
			break ;


		if (ord($x) != ord($y))
		{
			if (ctype_alpha($x)) {
				if (ctype_alpha($y)) {
					return (ord($x) - ord($y));
				} else {
					return (-1);	//alphas come first
				} 
			}
			
			if (ctype_alpha($y)) {
				return (1);			//alphas come first
			}
			
			if (ctype_digit($x)) {
				if (ctype_digit($y)) {
					return (ord($x) - ord($y));	//ord would work too
				} else  {
					return (-1);	//numeric before everything else
				}
			}

			if (ctype_digit($y)) {
				return (1);			//alpha < numberic < everything else
			} else {
				return (ord($x) - ord($y));
			}
		}
		$i++;
	}
	return (ord($x) - ord($y));
}

if ($argc > 1) {
	$str = "";
	for ($i = 1; $i < $argc; $i++) {
		$str .= " " . $argv[$i];
	}
	$split = explode(" ", $str);
	usort($split, "banana_sort");
	$alpha = [];
	$numbers = [];
	$others = [];
	for ($i = 0; $i < count($split); $i++) {
		if (strlen($split[$i]) > 0) {
			if (ctype_digit($split[$i][0])) {
				$numbers[] = $split[$i];
			} else if (ctype_alpha($split[$i][0])) {
				$alpha[] = $split[$i];
			} else {
				$others[] = $split[$i];
			}
		}
	}
//	var_dump($alpha);
	usort($alpha, "banana_sort");	//one more time in order to put the alnums at the end
	usort($numbers, "banana_sort");	//one more time in order to put the alnums at the end
	usort($others, "banana_sort");	//one more time in order to put the alnums at the end
	$res = array_merge($alpha, $numbers, $others);
	foreach ($res as $word) {
			echo "$word\n";
	}
}
?>

#!/usr/bin/php
<?php
while (($str = readline('Enter a number: ')) !== false){
	if (is_numeric($str)) {
		if (intval($str) % 2 == 0) {
			echo "The number $str is even\n";
		} else {
			echo "The number $str is odd\n";
		}
	} else {
		echo "'$str' is not a number\n";
	}
}
echo "\n";
?>

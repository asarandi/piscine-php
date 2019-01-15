#!/usr/bin/php
<?php
function ft_split($str) {
	$arr = explode(" ", $str);
	sort($arr);
	return ($arr);
}
print_r(ft_split("Hello World AAA"));
?>

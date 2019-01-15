#!/usr/bin/php
<?PHP
include("ft_is_sort.php");
$tab = array("!/@#;^", "42", "Hello World", "hi", "zZzZzZz");
$tab[] = "What are we doing now ?";
$baba[] = array(1, 2, 3, 4, 5, 6, 7, 8, 9);

var_dump($tab);
if (ft_is_sort($tab))
	echo "The array is sorted\n";
else
	echo "The array is not sorted\n";

var_dump($baba);
if (ft_is_sort($baba))
	echo "The array is sorted\n";
else
	echo "The array is not sorted\n";


?>

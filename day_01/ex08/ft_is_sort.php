<?php
/*

e1z4r13p9% php ex08/main.php
array(6) {
  [0]=>
  string(6) "!/@#;^"
  [1]=>
  string(2) "42"
  [2]=>
  string(11) "Hello World"
  [3]=>
  string(2) "hi"
  [4]=>
  string(7) "zZzZzZz"
  [5]=>
  string(23) "What are we doing now ?"
}
The array is not sorted
array(1) {
  [0]=>
  array(9) {
    [0]=>
    int(1)
    [1]=>
    int(2)
    [2]=>
    int(3)
    [3]=>
    int(4)
    [4]=>
    int(5)
    [5]=>
    int(6)
    [6]=>
    int(7)
    [7]=>
    int(8)
    [8]=>
    int(9)
  }
}
The array is sorted

e1z4r13p9% cat ex08/main.php
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


 */
function ft_is_sort($arr)
{
	$copy = $arr;
	sort($copy);

	return ($arr === $copy);
}
?>

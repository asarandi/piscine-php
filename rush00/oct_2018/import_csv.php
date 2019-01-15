#!/usr/bin/php
<?php

include 'subroutines.php';

function trim_array_strings($arr) {
	for ($i = 0; $i < count($arr); $i++) {
		$arr[$i] = trim($arr[$i]);
	}
	return ($arr);
}

if ($argc == 3) {
	$SEPARATOR = ',';
	$filename = $argv[1];

	if (($fp = fopen($filename, 'r')) === false)
		exit();

	$csv_keys = fgetcsv($fp, 0, $SEPARATOR);
	$csv_keys = trim_array_strings($csv_keys);

	if (!is_array($csv_keys))
		exit("failed :(    not a .csv file?");

	$books = [];
	while (($data = fgetcsv($fp, 0, $SEPARATOR)) !== false) {
		$data = trim_array_strings($data);
		$kv_array = [];
		for ($i = 0; $i < count($csv_keys); $i++) {
			$kv_array[$csv_keys[$i]] = $data[$i];
		}
		$books[] = $kv_array;
	}
	fclose($fp);

/// mysql stuff

	$column_names = '';
	foreach ($csv_keys as $column) {
		if ($column_names != '')
			$column_names .= ', ';
		$column_names .= $column;
	}

	create_tables();
	$db = mysql_connect();
	$table_name = trim($argv[2]);

	foreach ($books as $book) {
		$values = '';
		foreach ($book as $key => $value) {
			if ($values != '') {
				$values .= ', ';
			}
			$values .= "'" . mysqli_real_escape_string($db, addslashes($value)) . "'";
		}

		$query = "INSERT INTO $table_name ($column_names, active) VALUES ($values, 1);";
		if (mysqli_query($db, $query) !== TRUE) {
			echo "mysqli_query() failed :(\n";
		}
	}
	mysqli_close($db);

} else {
//e1z4r13p9% php -f import_csv.php BooksForPHP\ -\ genres.csv 'categories'
//e1z4r13p9% php -f import_csv.php BooksForPHP\ -\ titles.csv 'products'
	echo "  usage: php -f $argv[0] <comma_separated_values.file.csv> <mysql_table_name> \n";
//	echo "example: php -f $argv[0] 'books-for-php-titles.csv' 'products'\n";
}
?>

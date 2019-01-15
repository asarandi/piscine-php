#!/usr/bin/php
<?php

function get_db_to_array($name)
{
	$link = db_connect();
	if (!($res = mysqli_query($link, "SELECT $name")))
	{
		echo "mysqli_query failed\n";
		exit();
	}
	$rows = array();
	while ($arr = mysqli_fetch_array($res, MYSQLI_NUM))
		$rows[] = $arr;
	mysqli_close($link);
	return ($rows);
}

?>

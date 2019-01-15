#!/usr/bin/php
<?php

date_default_timezone_set("Europe/Paris");
setlocale(LC_ALL, 'fr_FR', 'fr');

if ($argc > 1) {
	$str = trim(strtolower($argv[1]));

	$french =	['/lundi/', '/mardi/', '/mercredi/', '/jeudi/', '/vendredi/', '/samedi/', '/dimanche/', '/janvier/', '/février/', '/mars/', '/avril/', '/mai/', '/juin/', '/juillet/', '/août/', '/septembre/', '/octobre/', '/novembre/', '/décembre/'];

	$english =	['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

	$translated = preg_replace($french, $english, $str);

	$format = "l j F Y H:i:s";
	if (($date = DateTime::createFromFormat($format, $translated)) === false) {
		exit("Wrong Format\n");
	}
	echo date_timestamp_get($date) . "\n";
}
?>

#!/usr/bin/php
<?php

stream_set_blocking(STDIN, 0);
$csv_keys = fgetcsv(STDIN, 0, ';');

$users = [];
if (is_array($csv_keys)){

	$moulinette_sum = 0;
	$moulinette_count = 0;

	$user_sums = [];
	$user_counts = [];
	$user_moulinette_grade = [];

	while (($buf = fgetcsv(STDIN, 0, ';')) !== false)
	{
		$user = $buf[0];
		$grade = intval($buf[1]);
		$corrector = $buf[2];
		$feedback = intval($buf[3]);

		if (strcmp($corrector, "moulinette") == 0) {
			$moulinette_sum += $grade;
			$moulinette_count += 1;
			$user_moulinette_grade[$user] = $grade;
		} else {
			if (!isset($user_sums[$user])) {
				$user_sums[$user] = 0;
			}
			if (!isset($user_counts[$user])) {
				$user_counts[$user] = 0;
			}

			if (strlen($buf[1]) > 0) {			//grade
				$user_sums[$user] += $grade;
				$user_counts[$user] += 1;
			}
		}
	}

	ksort($user_sums);
	$user_averages = [];

	$general_sum = 0;
	$general_count = 0;
	foreach ($user_sums as $k => $v) {
		$user_averages[$k] = $v / $user_counts[$k];
		$general_sum += $v;
		$general_count += $user_counts[$k];
	}

	$cmd_average = ["moyenne", "average"];
	$cmd_average_user = ["moyenne_user", "average_user"];
	$cmd_variance = ["ecart_moulinette", "moulinette_variance"];

	if (isset($argv[1])) {

		if (in_array($argv[1], $cmd_average)) {
			echo $general_sum / $general_count . "\n";
		} else if (in_array($argv[1], $cmd_average_user)) {
			foreach ($user_averages as $k => $v) {
				echo "$k:$v\n";
			}
		} else if (in_array($argv[1], $cmd_variance)) {
			foreach ($user_averages as $k => $v) {
				$variance = $v - $user_moulinette_grade[$k];
				echo "$k:$variance\n";
			}
		}
	}
}

?>

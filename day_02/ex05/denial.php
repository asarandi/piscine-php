#!/usr/bin/php
<?php
if ($argc == 3) {

	$filename = $argv[1];
	$master_key = $argv[2];

	if (($fp = fopen($filename, 'r')) === false)
		exit();

	$csv_keys = fgetcsv($fp, 0, ';');
	if (!is_array($csv_keys))
		exit();

	$master_i = 0;
	$found_flag = false;
	while ($master_i < count($csv_keys)) {
		if ($csv_keys[$master_i] == $master_key) {
			$found_flag = true;
			break ;
		}
		$master_i++;
	}

	if ($master_key == 'surname'){		//this is to comply with pdf's second example:
		$master_i = 1;					//[$> ./denial.php data.csv surname]
		$found_flag = true;				//'surname' is not in csv headers,
	}									//my guess is that "surname" is a direct translation of "prenom"
										//they should have kept it all in english,
										//i think they used an automatic translator for the pdf's :(

	if (!$found_flag)
		exit();

	$nom = [];
	$prenom = [];
	$mail = [];
	$IP = [];
	$pseudo = [];

	while (($data = fgetcsv($fp, 0, ';')) !== false) {
		$nom[$data[$master_i]] = $data[0];
		$prenom[$data[$master_i]] = $data[1];
		$mail[$data[$master_i]] = $data[2];
		$IP[$data[$master_i]] = $data[3];
		$pseudo[$data[$master_i]] = $data[4];
	}
	fclose($fp);
	$name = $nom;	//retarded ... but complying with example in pdf, where $name['miawallace'] returns 'Naline'
	$surname = $prenom;	//to comply with pdf
	$last_name = $nom;
	$lastname = $nom;
	$firstname = $prenom;
	$first_name = $prenom;
	
	while (($cmd = readline('Enter your command: ')) !== false){
		eval($cmd);
	}
	echo "\n";
}
?>

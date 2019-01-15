<?php

function insert_category($db, $name, $short_description, $description, $pictures, $active)
{
	$name				= mysqli_real_escape_string($db, $name);
	$short_description	= mysqli_real_escape_string($db, $short_description);
	$description		= mysqli_real_escape_string($db, $description);
	$pictures			= mysqli_real_escape_string($db, $pictures);
	$active				= mysqli_real_escape_string($db, $active);

	$query = "INSERT INTO categories (name, short_description, description, pictures, active) VALUES ('$name', '$short_description', '$description', '$pictures', $active);";
	mysqli_query($db, $query);

}

$mysql_addr = '10.114.13.9';
$mysql_user = 'root';
$mysql_pass = 'root';
$mysql_port = 8889;	//MAMP
$mysql_db_name = 'piscine_php_rush00';


$queries = [
	'CREATE DATABASE IF NOT EXISTS '.$mysql_db_name.';',
	'USE '.$mysql_db_name.';',
	'CREATE TABLE IF NOT EXISTS `users` ( `id` INT PRIMARY KEY AUTO_INCREMENT, `access_level` INT, `user_name` VARCHAR(255) UNIQUE, `password_hash` VARCHAR(255), `first_name` VARCHAR(255), `last_name` VARCHAR(255), email VARCHAR(255), phone VARCHAR(255), `deleted` INT DEFAULT 0  );',
	'CREATE TABLE IF NOT EXISTS `categories` ( `id` INT PRIMARY KEY AUTO_INCREMENT, `name` VARCHAR(255) UNIQUE, `short_description` VARCHAR(255), `description` TEXT, `pictures` TEXT, `active` INT, `deleted` INT DEFAULT 0 );',
	'CREATE TABLE IF NOT EXISTS `products` ( `id` INT PRIMARY KEY AUTO_INCREMENT, `title` VARCHAR(255) UNIQUE, `author` VARCHAR(255), `isbn13` VARCHAR(255), `description` TEXT, `publication_date` VARCHAR(255), `price` VARCHAR(255),  `pictures` TEXT, `categories` VARCHAR(255), `active` INT, `deleted` INT DEFAULT 0 );',
	'CREATE TABLE IF NOT EXISTS `orders` ( `id` INT PRIMARY KEY AUTO_INCREMENT, `user_id` INT, `products` TEXT, `quantities` TEXT, `status` INT, `deleted` INT DEFAULT 0 );'];

$db = mysqli_connect($mysql_addr, $mysql_user, $mysql_pass, '', $mysql_port);

if (!$db) {
    exit("Unable to connect to mysql\n");
}

foreach ($queries as $query) {
	mysqli_query($db, $query);
}



	$short_description = "We wants it, we needs it. Must have the precious.";
	$description = "Fantasy literature is literature set in an imaginary universe, often but not always without any locations, events, or people from the real world. Magic, the supernatural and magical creatures are common in many of these imaginary worlds. Fantasy is a subgenre of speculative fiction and is distinguished from the genres of science fiction and horror by the absence of scientific or macabre themes, respectively, though these genres overlap. Historically, most works of fantasy were written, however, since the 1960s, a growing segment of the fantasy genre has taken the form of films, television programs, graphic novels, video games, music and art.";
	$name = "Fantasy";
	$pictures = 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8b/1ABC-character-design.png/2880px-1ABC-character-design.png';
	insert_category($db, $name, $short_description, $description, $pictures, 1);


	$short_description = 'Crime fiction is the literary genre that fictionalises crimes, their detection, criminals, and their motives.';
	$description = 'Crime fiction is usually distinguished from mainstream fiction and other genres such as historical fiction or science fiction, but the boundaries are indistinct. Crime fiction has multiple subgenres, including detective fiction (such as the whodunit), courtroom drama, hard-boiled fiction and legal thrillers. Most crime drama focus on crime investigation and does not feature the court room. Suspense and mystery are key elements that are nearly ubiquitous to the genre.';
	$name = 'Crime';
	$pictures = 'https://upload.wikimedia.org/wikipedia/commons/7/73/Paget_holmes.png';
	insert_category($db, $name, $short_description, $description, $pictures, 1);



	$short_description = 'Fictional works with faith-based themes.';
	$description = 'Inspirational fiction may be targeted at a specific demographic, such as Christians. Modern inspirational fiction has grown to encompass non-traditional subgenres, such as inspirational thrillers.';
	$name = 'Inspirational';
	$pictures = 'https://images-na.ssl-images-amazon.com/images/I/51Ck4T6j9CL.jpg';
	insert_category($db, $name, $short_description, $description, $pictures, 1);



	mysqli_close($db);
?>

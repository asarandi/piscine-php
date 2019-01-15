<?php
	include 'subroutines.php';



create_tables();

$db = mysql_connect();

$queries = [ "insert into users (access_level, user_name, password_hash, active) values(1, 'admin1', '6c00a68c5aefab64a3e14f796fc9863bbdded9a882a904febaaa1ca875afb510d233e10f9ed1e05cee8bf0f60c9cbc528f30e7f8ec39c91986048d0b9d43229a', 1);",
	"insert into users (access_level, user_name, password_hash, active) values (2, 'alex1', '693557a2aa42783402ebc033a74c16821e59224aa91f57cf210e4c2bf6712fb0f08f561031711799a77b87224fbd28167fbc29772bc580dcc77e961f1e783a0a', 1);",
	"insert into users (access_level, user_name, password_hash, active) values (2, 'liz1', '9374400757d40020a07d310c8bedf12c865dff9f84504321d7da06f1441dbe9e6eb0cb1ac13e7930f18b324299ac354bb13d7e87fda7cfdf2712da67a9f28ad9', 1);"];
 

foreach ($queries as $query) {
	mysqli_query($db, $query);
}

mysqli_close($db);

/*

	admin:banana42
	alex:alex
	liz:liz

insert into users (access_level, user_name, password_hash, active) values(1, 'admin', '6c00a68c5aefab64a3e14f796fc9863bbdded9a882a904febaaa1ca875afb510d233e10f9ed1e05cee8bf0f60c9cbc528f30e7f8ec39c91986048d0b9d43229a', 1);
insert into users (access_level, user_name, password_hash, active) values (2, 'alex', '693557a2aa42783402ebc033a74c16821e59224aa91f57cf210e4c2bf6712fb0f08f561031711799a77b87224fbd28167fbc29772bc580dcc77e961f1e783a0a', 1);
insert into users (access_level, user_name, password_hash, active) values (2, 'liz', '9374400757d40020a07d310c8bedf12c865dff9f84504321d7da06f1441dbe9e6eb0cb1ac13e7930f18b324299ac354bb13d7e87fda7cfdf2712da67a9f28ad9', 1);
 */



?>

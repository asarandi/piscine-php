<?php

// ~/.brew/opt/mysql-client/bin/mysql -h 127.0.0.1 --port 8889 -u root -proot
// grant all privileges on *.* to 'root'\''10.114.13.7' identified by 'root' with grant option; flush privileges;
//array(2) { ["delete_account"]=> string(1) "0" ["sign_out"]=> string(1) "1" } 
//array(5) { ["login"]=> string(9) "asdasdasd" ["passwd"]=> string(12) "asdasdasdasd" ["login_uname"]=> string(0) "" ["login_psw"]=> string(0) "" ["current_url"]=> string(45) "http://localhost/piscine_php_rush00/genre.php" }

$mysql_addr = '10.114.13.9';
$mysql_user = 'root';
$mysql_pass = 'root';
$mysql_port = 8889;
$mysql_db_name = 'piscine_php_rush00';


if (!isset($_SESSION)){
	session_start();
};

if (isset($_POST)) {

	if (count($_POST) > 0) {
//	var_dump($_POST);
		if (isset($_POST['login_uname']) && strlen($_POST['login_uname']) > 0) {
			if (isset($_POST['login_psw']) && strlen($_POST['login_psw']) > 0) {
				$_SESSION['login_uname'] = $_POST['login_uname'];
				$_SESSION['login_psw'] = hash('whirlpool', $_POST['login_psw']);
			}
		}
	if (isset($_POST['sign_out'])){
		if ($_POST['sign_out'] == "1"){
			if (is_logged_in() === true) {
				$_SESSION['login_uname'] = '';
				$_SESSION['login_psw'] = '';
			}
		}
//		var_dump($_SESSION);
	}
		
	if (isset($_POST['add_to_basket'])) {
			if (isset($_SESSION['basket_items'])) {
				$_SESSION['basket_items'] .= ',' . $_POST['add_to_basket'];
			} else {
				$_SESSION['basket_items'] = $_POST['add_to_basket'];
			}
	}

	if (isset($_POST['clear_basket'])) {
			unset($_SESSION['basket_items']);
//			$_SESSION['basket_items'] = '';
	}



	}
//	header("Location: index.php");// . $_SESSION['current_url']);
}

//echo "HAHAHA" . $_SESSION['current_url'];

$current_url = '';
if ((isset($_SERVER['SERVER_NAME'])) && (isset($_SERVER['REQUEST_URI']))) {
	$current_url = 'http://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	if (isset($_GET) && count($_GET) > 0)
	{
		$current_url .= '?';
	}
	foreach ($_GET as $key => $value) {
		$current_url .= "$key=$value";
	}
	$_SESSION['current_url'] = $current_url;
}


//echo "<pre>ALEX$current_url</pre>\n";


function is_logged_in()
{
	if (isset($_SESSION)) {
		if (isset($_SESSION['login_uname'])) {
			if (isset($_SESSION['login_psw'])) {
				if (is_valid_user($_SESSION['login_uname'], $_SESSION['login_psw']) === 1) {
					return true;
				}
			}
		}
	}
	return false;
}

function is_valid_user($user_name, $password_hash) {
	if ((strlen($user_name) < 1) || (strlen($password_hash) != 128))
		return -1;

	$db = mysql_connect();
	$user_name = mysqli_real_escape_string($db, $user_name);
	$query = "SELECT password_hash FROM users WHERE user_name='$user_name';";

	$return_value = 0;

	if (($result = mysqli_query($db, $query)) !== NULL) {
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

		if (($row !== NULL) && (strcmp($row['password_hash'], $password_hash) == 0)) {
			$return_value = 1;
			} else {
			$return_value = -2;	//wrong password hash
			};
		} else {
		$return_value = -1;		//query failed; no such user?
		};
	mysqli_free_result($result);
	mysqli_close($db);
	return ($return_value);
}

function mysql_connect() {
	global $mysql_addr, $mysql_user, $mysql_pass, $mysql_port, $mysql_db_name;
	$mysql_connection = mysqli_connect($mysql_addr, $mysql_user, $mysql_pass, $mysql_db_name, $mysql_port);
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
    	exit();
	}
	return ($mysql_connection);
}

function create_tables(){
	global $mysql_db_name;

	$queries = [
		'CREATE DATABASE IF NOT EXISTS '.$mysql_db_name.';',
		'USE '.$mysql_db_name.';',
		'CREATE TABLE IF NOT EXISTS `users` ( `id` INT PRIMARY KEY AUTO_INCREMENT, `access_level` INT, `user_name` VARCHAR(255) UNIQUE, `password_hash` VARCHAR(255), `first_name` VARCHAR(255), `last_name` VARCHAR(255), email VARCHAR(255), phone VARCHAR(255), `active` INT, `deleted` INT DEFAULT 0  );',
		'CREATE TABLE IF NOT EXISTS `categories` ( `id` INT PRIMARY KEY AUTO_INCREMENT, `name` VARCHAR(255) UNIQUE, `short_description` VARCHAR(255), `description` TEXT, `pictures` TEXT, `active` INT, `deleted` INT DEFAULT 0 );',
		'CREATE TABLE IF NOT EXISTS `products` ( `id` INT PRIMARY KEY AUTO_INCREMENT, `title` VARCHAR(255) UNIQUE, `author` VARCHAR(255), `isbn13` VARCHAR(255), `description` TEXT, `publication_date` VARCHAR(255), `price` VARCHAR(255),  `pictures` TEXT, `categories` VARCHAR(255), `active` INT, `deleted` INT DEFAULT 0 );',
		'CREATE TABLE IF NOT EXISTS `orders` ( `id` INT PRIMARY KEY AUTO_INCREMENT, `user_id` INT, `products` TEXT, `quantities` TEXT, `status` INT, `deleted` INT DEFAULT 0 );'];

	$db = mysql_connect();
	if (!$db) {
	    exit("Unable to connect to mysql\n");
	}

	foreach ($queries as $query) {
		mysqli_query($db, $query);
	}
	mysqli_close($db);
}

// http://php.net/manual/en/function.srand.php
// seed with microseconds

function make_seed() {
  list($usec, $sec) = explode(' ', microtime());
  return $sec + $usec * 1000000;
}

function random_in_range($minimum, $maximum) {

	srand(make_seed());
	$randval = rand();

	return ($minimum + (($randval % $maximum) - $minimum + 1));
}

function show_sidenav() {
    echo '<div class="sidenav">' . "\n";
    echo '    <h1 style="color:white; margin-left: 25px">Genres</h1>' . "\n";

    $db = mysql_connect();

    $query = 'SELECT id, name FROM categories WHERE active=1 ORDER BY name ASC;';
    $result = mysqli_query($db, $query);
    $menu = [];

    while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) !== NULL) {
        $menu[$row['id']] = $row['name'];
        echo '<a href="genre.php?id=' . $row['id'] . '">' . $row['name'] .'</a>' . "\n";
    }
    mysqli_free_result($result);
    mysqli_close($db);
    echo "</div>\n";
}

function show_footer() {
	echo '<footer>' . "\n";
	echo '	    <div class="main footer">' . "\n";
	echo '	        <div style="float:right"><tt>&#169 asarandi & edehmlow</tt></div>' . "\n";
	echo '	        <div style="clear: left;"> Icons made by <a href="https://www.flaticon.com/authors/freepik" title="Man User">Man User</a> from <a href="https://www.flaticon.com/"     title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/"     title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>' . "\n";
	echo '	    </div>' . "\n";
	echo '	</footer>' . "\n";
}


function show_topbar() {
	echo '<div class="main">' . "\n";
	echo '<ul>' . "\n";
	echo '<li><a class="active" href="index.php" style="height: 64px">BookStore</a></li>' . "\n";
	echo '<li style="float:right"><a href="basket.php"><img src="resources/shopping-bag-white.png" alt="basket" title="user" height="30" width="30"></a></li>' . "\n";
	$modal_id = "'id01'";
	$modal_icon = 'resources/man-user-white.png';
	$welcome_greeting = '';
	if (is_logged_in() === true) {
//		echo ' <h1> YOU ARE LOGGED IN !!!!!! </h1>';
		$modal_id = "'id03'";
		$modal_icon = 'resources/man-user-red.png';
		//$welcome_greeting = "welcome, " . $_SESSION['login_uname'];
		$welcome_greeting = $_SESSION['login_uname'];
	}
	echo '<li style="float:right"><a><img src="'.$modal_icon.'" alt="user" title="user" height="30" width="30" onclick="document.getElementById('.$modal_id.').style.display=\'block\'" style="width:auto;"></a></li>' . "\n";
	echo '<li style="float:right; font-size: 48px;">'.$welcome_greeting.'</li>' . "\n";
	echo '</ul>' . "\n";
	echo '</div>' . "\n";
}

function show_modal() {

	if (is_logged_in() !== true){
		echo '<div id="id01" class="modal">' . "\n";
		echo '	<form class="modal-content" action="' . $_SERVER['PHP_SELF'] . '" method="POST">' . "\n";			///FORM
		echo '		<div class="imgcontainer">' . "\n";
		echo '			<span onclick="document.getElementById(\'id01\').style.display=\'none\'" class="close" title="Close Modal">&times;</span>' . "\n";
		echo '			<img src="resources/man-user.png" alt="User" class="user">' . "\n";
		echo '		</div>' . "\n";
		echo '		<div class="container">' . "\n";
		echo '			<label for="login_uname"><b>Username</b></label>' . "\n";
		echo '			<input type="text" placeholder="Enter Username" name="login_uname" required>' . "\n";		//login_uname
		echo '' . "\n";
		echo '			<label for="login_psw"><b>Password</b></label>' . "\n";
		echo '			<input type="password" placeholder="Enter Password" name="login_psw" required>' . "\n";		//login_psw
		echo '' . "\n";
		echo '			<button type="submit">Login</button>' . "\n";									////LOGIN
		echo '			<span class="psw"><a style="color: blue" onclick="document.getElementById(\'id02\').style.display=\'block\'; document.getElementById(\'id01\').style.display=\'none\'" style="width:auto;">New User?</a></span>' . "\n";
		echo '		</div>' . "\n";
		echo '	</form>' . "\n";
		echo '</div>' . "\n";
		echo '<div id="id02" class="modal">' . "\n";
		echo '	<form class="modal-content" action="' . $_SERVER['PHP_SELF'] . '" method="POST">' . "\n";			///FORM

		echo '<div class="imgcontainer">' . "\n";
		echo '<span onclick="document.getElementById(\'id02\').style.display=\'none\'" class="close" title="Close Modal">&times;</span>' . "\n";
		echo '<img src="resources/man-user.png" alt="User" class="user">' . "\n";
		echo '</div>' . "\n";
		echo '<div class="container">' . "\n";
		echo '<label for="uname"><b>Username</b></label>' . "\n";
		echo '<input type="text" placeholder="Enter Username" name="register_uname" required>' . "\n";		//register_uname
		echo '' . "\n";
		echo '<label for="psw"><b>Password</b></label>' . "\n";
		echo '<input type="password" placeholder="Enter Password" name="register_psw1" required>' . "\n";	//register_psw1
		echo '' . "\n";
		echo '<label for="psw"><b>Repeat Password</b></label>' . "\n";
		echo '<input type="password" placeholder="Enter Password" name="register_psw2" required>' . "\n";	//register_psw2
		echo '' . "\n";
		echo '<button type="submit">Create Account</button>' . "\n";										////CREATE ACCOUNT
		echo '<span class="psw"><a style="color: blue" onclick="document.getElementById(\'id01\').style.display=\'block\'; document.getElementById(\'id02\').style.display=\'none\'" style="width:auto;">Already have an account?</a></span>' . "\n";
		echo '</div>' . "\n";
		echo '</form>' . "\n";
		echo '</div>' . "\n";
		return ;
}

if (is_logged_in() === true) {
		echo '<div id="id03" class="modal">' . "\n";
		echo '	<form class="modal-content" name="signout" id="signout" action="' . $_SERVER['PHP_SELF'] . '" method="POST">' . "\n";			///FORM
		echo '  <input type="hidden" id="delete_account" name="delete_account" value="0">' . "\n";
		echo '  <input type="hidden" id="sign_out" name="sign_out" value="0">' . "\n";
		echo '	<div class="imgcontainer">' . "\n";
		echo '		<span onclick="document.getElementById(\'id03\').style.display=\'none\'" class="close" title="Close Modal">&times;</span>' . "\n";
		echo '		<img src="resources/man-user-red.png" alt="User" class="user">' . "\n";
		echo '	</div>' . "\n";
		echo '	<div class="container">' . "\n";
		echo '		<p>You are signed in.</p>' . "\n";
		echo '		<button style="color: red" type="submit" onclick="document.getElementById(\'id03\').style.display=\'none\'; document.getElementById(\'delete_account\').value=\'1\'; document.signout.submit();">Delete Account</button>' . "\n";
		echo '		<button                    type="submit" onclick="document.getElementById(\'id03\').style.display=\'none\'; document.getElementById(\'sign_out\').value=\'1\'; document.signout.submit();">Sign Out</button>' . "\n";
		echo '	</div>' . "\n";
		echo '</form>' . "\n";
		echo '</div>' . "\n";
		return ;
	}
}

?>

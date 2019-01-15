<?php
$valid_user = 'zaz';
$valid_password = 'jaimelespetitsponeys';

$user = '';
if (isset($_SERVER['PHP_AUTH_USER']))
	$user = $_SERVER['PHP_AUTH_USER'];
$password = '';
if (isset($_SERVER['PHP_AUTH_PW']))
	$password = $_SERVER['PHP_AUTH_PW'];

if ((strcmp($user, $valid_user) == 0) && (strcmp($password, $valid_password) == 0)) {
	echo "<html><body>\nHello Zaz<br />\n<img src='data:image/png;base64,";
	echo base64_encode(file_get_contents('../img/42.png'));
	echo "'>\n</body></html>\n";
} else {
	header("WWW-Authenticate: Basic realm=''Member area''");
	header('HTTP/1.0 401 Unauthorized');
	echo "<html><body>That area is accessible for members only</body></html>\n";
}
?>

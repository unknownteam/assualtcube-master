<?php
require_once "library/User.php";
require_once "library/Server.php";

header('Content-type: text/plain');

function serverLogin() {

	//Grab input
	$username = $_REQUEST["username"];
	$password = $_REQUEST["password"];
	$server = $_REQUEST["server"];
	
	$user = User::getUser($username);
	
	if($user === null || !$user->checkPassword($password)) {
		throw new Exception("Error: Username/Password combo was incorrect.");
	}
	
	$server = Server::getServerByPublicKey($server);
	
	if($server == null) {
		throw new Exception("Error: Server not found!");
	}
	
	//Produce authentication key
	$serverAuthCode = md5($server->getPrivateKey() . $user->getUsername);
	
	return $serverAuthCode . "\n" . $server->getAddress() . "\n" . $server->getPort();
}
try {
	echo serverLogin(); echo SID;
} catch (Exception $e) {
	echo $e->getMessage();
}
?>

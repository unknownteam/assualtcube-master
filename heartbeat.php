<?php
require_once "library/Server.php";
header('Content-type: text/plain');
function ServerPulse() {
	$server = new Server();
	
	//Get server address
	$server->setAddress($_SERVER['REMOTE_ADDR']);
	
	//Grab input
	$server->setPort($_REQUEST["port"]);
	$server->setName($_REQUEST["name"]);
	$server->setVersion($_REQUEST["version"]);
	$server->setPrivateKey($_REQUEST["fingerprint"]);
	$server->setMaxClients($_REQUEST["max"]);
	$server->setPublic($_REQUEST["public"]);
	$server->setPasswordProtected($_REQUEST["passwordProtected"]);
	$server->setAllowGuests($_REQUEST["allowGuests"]);
	$server->setUserCount($_REQUEST["users"]);
	$server->setUserList($_REQUEST["players"]);
	$server->setMotd($_REQUEST["motd"]);
	$server->setGameMode($_REQUEST["gamemode"]);
	
	//Update (or create) server record
	return Server::updateServer($server);
}
try {
	echo ServerPulse();
} catch (Exception $e) {
	echo $e->getMessage();
}
?>
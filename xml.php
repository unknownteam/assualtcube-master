<?php
require_once "library/Server.php";

header('Content-Type: text/xml'); 

function GenerateXML() {
	$servers = Server::getServerList();

	$dom = new DomDocument;
	$serverList = $dom->appendChild($dom->createElement("ServerList"));
	
	foreach($servers as $s) {
		$server = $serverList->appendChild($dom->createElement("Server"));
		
		//Setup structure
		$hash = $server->appendChild($dom->CreateElement("Hash"));
		$name = $server->appendChild($dom->CreateElement("Name"));
		$motd = $server->appendChild($dom->CreateElement("MOTD"));
		$port = $server->appendChild($dom->CreateElement("Port"));
		$ip = $server->appendChild($dom->CreateElement("IP"));
		$version = $server->appendChild($dom->CreateElement("Version"));
		$users = $server->appendChild($dom->CreateElement("Users"));
		$max = $server->appendChild($dom->CreateElement("Max"));
		$gameMode = $server->appendChild($dom->CreateElement("GameMode"));
		$players = $server->appendChild($dom->CreateElement("Players"));	
		
		//Fill with values
		$hash->appendChild($dom->CreateTextNode($s->getPublicKey()));
		$name->appendChild($dom->CreateTextNode($s->getName()));
		$motd->appendChild($dom->CreateTextNode($s->getMotd()));
		$port->appendChild($dom->CreateTextNode($s->getPort()));
		$ip->appendChild($dom->CreateTextNode($s->getAddress()));
		$version->appendChild($dom->CreateTextNode($s->getVersion()));
		$users->appendChild($dom->CreateTextNode($s->getUserCount()));
		$max->appendChild($dom->CreateTextNode($s->getMaxClients()));
		$gameMode->appendChild($dom->CreateTextNode($s->getGameMode()));
		$players->appendChild($dom->CreateTextNode($s->getUserList()));
	}
	return $dom->saveXml();
}
echo GenerateXML();
?>
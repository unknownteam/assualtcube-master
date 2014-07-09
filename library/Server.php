<?php
require_once "Utility.php";

class Server {

	//Static Functions
	public static function updateServer(Server $server) {
		if(Server::getServerByPrivateKey($server->getPrivateKey()) === null) {
			return Server::createServer($server);
		}
	
		//Grab connection object
		$mysqli = Utility::getSQLConnection();
		
		$sql =  "UPDATE servers SET ";
		$sql .= "address = ?, port = ?, version = ?, name = ?, key_hash = ?, max_clients = ?, ";
		$sql .= "public = ?, password_protected = ?, allow_guests = ?, user_count = ?, ";
		$sql .= "user_list = ?, motd = ?, game_mode = ?, last_heartbeat_date = NOW() ";
		$sql .= "WHERE private_key = ?";
		
		
		$stmt = $mysqli->prepare($sql);
		if ($mysqli->errno) {
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		//Bind parameters
		$stmt->bind_param("sissssississss", 
			$server->getAddress(), 
			$server->getPort(), 
			$server->getVersion(),
			$server->getName(), 
			$server->getPublicKey(),
			$server->getMaxClients(),
			$server->getPublic(),
			$server->getPasswordProtected(),
			$server->getAllowGuests(),
			$server->getUserCount(),
			$server->getUserList(),
			$server->getMotd(),
			$server->getGameMode(),
			$server->getPrivateKey());
		
		//Execute statement
		$stmt->execute();
		
		if ($mysqli->errno) {
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		$stmt->close();
		unset($stmt);
		
		return "Server updated succesfully.\nserver=" . $server->getPublicKey();
	}
	
	//Note: this is private.
	private static function createServer(Server $server) {
		//Grab connection object
		$mysqli = Utility::getSQLConnection();
		
		$sql =  "INSERT INTO servers(";
		$sql .= "address, port, version, name, private_key, key_hash, max_clients, public, password_protected, ";
		$sql .= "allow_guests, user_count, user_list, motd, game_mode, last_heartbeat_date";
		$sql .= ") VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())";
		
		$stmt = $mysqli->prepare($sql);
		if ($mysqli->errno) {
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		//Bind parameters
		$stmt->bind_param("sissssisssisss", 
			$server->getAddress(), 
			$server->getPort(),
			$server->getVersion(),
			$server->getName(), 
			$server->getPrivateKey(),
			$server->getPublicKey(),
			$server->getMaxClients(),
			$server->getPublic(),
			$server->getPasswordProtected(),
			$server->getAllowGuests(),
			$server->getUserCount(),
			$server->getUserList(),
			$server->getMotd(),
			$server->getGameMode());
			
		//Execute statement
		$stmt->execute();
		
		if ($mysqli->errno) {
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		$stmt->close();
		unset($stmt);
		
		return "Server added successfully.\nserver=" . $server->getPublicKey();
	}
	
	public static function getServerByPublicKey($keyHash) {
		$server = new Server();
		
		//Grab connection object
		$mysqli = Utility::getSQLConnection();
		
		$sql =  "SELECT server_id, address, port, version, name, private_key, key_hash, ";
		$sql .= "max_clients, public, password_protected, allow_guests, user_count, ";
		$sql .= "user_list, motd, game_mode, last_heartbeat_date, created_date ";
		$sql .= "FROM servers WHERE key_hash = ?";
		
		//Prepare query to find server
		$stmt = $mysqli->prepare($sql);
		if ($mysqli->errno) {
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		$stmt->bind_param("s", $keyHash);
		
		//Run query
		$stmt->execute();
		if ($mysqli->errno) {
			$stmt->close();
		    unset($stmt);
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		$stmt->bind_result($serverId, $address, $port, $version, $name, $privateKey, $keyHash, 
						   $maxClients, $public, $passwordProtected, $allowGuests, $userCount, 
						   $userList, $motd, $gameMode, $lastHeartbeatDate, $createdDate);
		
		$result = $stmt->fetch();
		
		$stmt->close();
		unset($stmt);
		
		if(!$result) { //Not found or error.
			return null; 
		}		
		
		//Fill server object
		$server->setServerId($serverId);
		$server->setAddress($address);
		$server->setPort($port);
		$server->setVersion($version);
		$server->setName($name);
		$server->setPrivateKey($privateKey);
		$server->setPublicKey($keyHash);
		$server->setMaxClients($maxClients);
		$server->setPublic($public);
		$server->setPasswordProtected($passwordProtected);
		$server->setAllowGuests($allowGuests);
		$server->setUserCount($userCount);
		$server->setUserList($userList);
		$server->setMotd($motd);
		$server->setGameMode($gameMode);
		$server->setLastHeartbeatDate($lastHeartbeatDate);
		$server->setCreatedDate($createdDate);
				
		return $server;
	}
	
	public static function getServerByPrivateKey($privateKey) {
		$server = new Server();
		
		//Grab connection object
		$mysqli = Utility::getSQLConnection();
		
		$sql =  "SELECT server_id, address, port, version, name, private_key, key_hash, ";
		$sql .= "max_clients, public, password_protected, allow_guests, user_count, ";
		$sql .= "user_list, motd, game_mode, last_heartbeat_date, created_date ";
		$sql .= "FROM servers WHERE private_key = ?";
		
		//Prepare query to find server
		$stmt = $mysqli->prepare($sql);
		if ($mysqli->errno) {
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		$stmt->bind_param("s", $privateKey);
		
		//Run query
		$stmt->execute();
		if ($mysqli->errno) {
			$stmt->close();
		    unset($stmt);
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		$stmt->bind_result($serverId, $address, $port, $version, $name, $privateKey, $keyHash, 
						   $maxClients, $public, $passwordProtected, $allowGuests, $userCount, 
						   $userList, $motd, $gameMode, $lastHeartbeatDate, $createdDate);
		
		$result = $stmt->fetch();
		
		$stmt->close();
		unset($stmt);
		
		if(!$result) { //Not found or error.
			return null; 
		}		
		
		//Fill server object
		$server->setServerId($serverId);
		$server->setAddress($address);
		$server->setPort($port);
		$server->setVersion($version);
		$server->setName($name);
		$server->setPrivateKey($privateKey);
		$server->setPublicKey($keyHash);
		$server->setMaxClients($maxClients);
		$server->setPublic($public);
		$server->setPasswordProtected($passwordProtected);
		$server->setAllowGuests($allowGuests);
		$server->setUserCount($userCount);
		$server->setUserList($userList);
		$server->setMotd($motd);
		$server->setGameMode($gameMode);
		$server->setLastHeartbeatDate($lastHeartbeatDate);
		$server->setCreatedDate($createdDate);
				
		return $server;
	}
	
	public static function getServerList() {
		//Grab connection object
		$mysqli = Utility::getSQLConnection();
		
		$sql =  "SELECT server_id, address, port, version, name, private_key, key_hash, ";
		$sql .= "max_clients, public, password_protected, allow_guests, user_count, ";
		$sql .= "user_list, motd, game_mode, last_heartbeat_date, created_date ";
		$sql .= "FROM servers ";
		$sql .= "WHERE last_heartbeat_date > (NOW() - INTERVAL 8 HOUR) ";
		$sql .= "ORDER BY last_heartbeat_date DESC";
		
		//Prepare query to find server
		$stmt = $mysqli->prepare($sql);
		if ($mysqli->errno) {
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		//Run query
		$stmt->execute();
		if ($mysqli->errno) {
			$stmt->close();
		    unset($stmt);
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		$stmt->bind_result($serverId, $address, $port, $version, $name, $privateKey, $keyHash, 
						   $maxClients, $public, $passwordProtected, $allowGuests, $userCount, 
						   $userList, $motd, $gameMode, $lastHeartbeatDate, $createdDate);
		
		$serverList = array();
		while($stmt->fetch()) {
			$server = new Server();
		
			//Fill server object
			$server->setServerId($serverId);
			$server->setAddress($address);
			$server->setPort($port);
			$server->setVersion($version);
			$server->setName($name);
			$server->setPrivateKey($privateKey);
			$server->setPublicKey($keyHash);
			$server->setMaxClients($maxClients);
			$server->setPublic($public);
			$server->setPasswordProtected($passwordProtected);
			$server->setAllowGuests($allowGuests);
			$server->setUserCount($userCount);
			$server->setUserList($userList);
			$server->setMotd($motd);
			$server->setGameMode($gameMode);
			$server->setLastHeartbeatDate($lastHeartbeatDate);
			$server->setCreatedDate($createdDate);
			
			$serverList[] = $server;
		}
		
		$stmt->close();
		unset($stmt);	

		return $serverList;
	}
	
	//Internal Data
	private $serverId;
	private $address;
	private $port;
	private $version;
	private $name;
	private $privateKey;
	private $keyHash;
	private $maxClients;
	private $public;
	private $passwordProtected;
	private $allowGuests;
	private $userCount;
	private $userList;
	private $motd;
	private $gameMode;
	private $createdDate;
	private $lastHeartbeatDate;
	
	
	//Getters/Setters
	public function getServerId(){
		return $this->serverId;
	}

	public function setServerId($serverId){
		$this->serverId = $serverId;
	}

	public function getAddress(){
		return $this->address;
	}

	public function setAddress($address){
		if(strlen($address) > 1024) {
			throw new Exception("Error: Server address length exceeded. (Max 1024)");
		}
		$this->address = $address;
	}

	public function getPort(){
		return $this->port;
	}

	public function setPort($port){
		$port = intval($port);
		if($port > 65535 || $port < 0) {
			throw new Exception("Error: Port number out of range.");
		}
		$this->port = $port;
	}
	
	public function getVersion(){
		return $this->version;
	}

	public function setVersion($version){
		if(strlen($version) > 32) {
			throw new Exception("Error: Version length exceeded. (Max 32)");
		}
		$this->version = $version;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		if(strlen($name) > 1024) {
			$name = substr($name, 0, 1024);
		}
		$this->name = $name;
	}

	public function getPrivateKey(){
		return $this->privateKey;
	}

	public function setPrivateKey($privateKey){
		if(strlen($privateKey) > 32) {
			throw new Exception("Error: Private key length exceeded. (Max 32)");
		}
		$this->keyHash = md5($privateKey . Config::webServerHash);
		$this->privateKey = $privateKey;
	}

	public function getPublicKey(){
		return $this->keyHash;
	}

	public function setPublicKey($keyHash){
		if(strlen($keyHash) > 32) {
			throw new Exception("Error: Public key length exceeded. (Max 32)");
		}
		$this->keyHash = $keyHash;
	}

	public function getMaxClients(){
		return $this->maxClients;
	}

	public function setMaxClients($maxClients){
		$this->maxClients = intval($maxClients);
	}

	public function getPublic(){
		return $this->public;
	}

	public function setPublic($public){
		if(strlen($public) > 5) {
			throw new Exception("Error: Public value length exceeded. (Max 5)");
		}
		$this->public = $public;
	}

	public function getPasswordProtected(){
		if(strtoupper($this->passwordProtected) === "TRUE") {
			return "True";
		} else {
			return "False";
		}
	}

	public function setPasswordProtected($passwordProtected){
		if(strlen($passwordProtected) > 5) {
			throw new Exception("Error: Password protected value length exceeded. (Max 5)");
		}
		$this->passwordProtected = $passwordProtected;
	}

	public function getAllowGuests(){
		return $this->allowGuests;
	}

	public function setAllowGuests($allowGuests){
		if(strlen($allowGuests) > 5) {
			throw new Exception("Error: Allow guests value length exceeded. (Max 5)");
		}
		$this->allowGuests = $allowGuests;
	}

	public function getUserCount(){
		return $this->userCount;
	}

	public function setUserCount($userCount){
		$this->userCount = intval($userCount);
	}

	public function getUserList(){
		return $this->userList;
	}

	public function setUserList($userList){
		if(strlen($userList) > 1024) {
			$userList = substr($userList, 0, 1024);
		}
		$this->userList = $userList;
	}

	public function getMotd(){
		return $this->motd;
	}

	public function setMotd($motd){
		if(strlen($motd) > 1024) {
			$motd = substr($motd, 0, 1024);
		}
		$this->motd = $motd;
	}

	public function getGameMode(){
		return $this->gameMode;
	}

	public function setGameMode($gameMode){
		if(strlen($gameMode) > 32) {
			$gameMode = substr($gameMode, 0, 32);
		}
		$this->gameMode = $gameMode;
	}

	public function getCreatedDate(){
		return $this->createdDate;
	}

	public function setCreatedDate($createdDate){
		$this->createdDate = $createdDate;
	}

	public function getLastHeartbeatDate(){
		return $this->lastHeartbeatDate;
	}

	public function setLastHeartbeatDate($lastHeartbeatDate){
		$this->lastHeartbeatDate = $lastHeartbeatDate;
	}
}
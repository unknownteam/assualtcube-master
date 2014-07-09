<?php
require_once "Utility.php";

class User {

	//Static Functions
	public static function getUser($username) {
		$user = new User();
		
		//Grab connection object
		$mysqli = Utility::getSQLConnection();
		
		//Prepare query to find user
		$stmt = $mysqli->prepare("SELECT user_id, username, password_hash, email, joined_date, last_login_date FROM users WHERE UPPER(username) = UPPER(?)");
		if ($mysqli->errno) {
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		$stmt->bind_param("s", $username);
		
		//Run query
		$stmt->execute();
		if ($mysqli->errno) {
			$stmt->close();
		    unset($stmt);
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		$stmt->bind_result($userId, $username, $passwordHash, $email, $joinedDate, $lastLoginDate);
		
		$result = $stmt->fetch();
		
		$stmt->close();
		unset($stmt);
		
		if(!$result) { //Not found or error.
			return null; 
		}		
		
		//Fill user object
		$user->setUserId($userId);
		$user->setUsername($username);
		$user->setPasswordHash($passwordHash);
		$user->setEmail($email);
		$user->setJoinedDate($joinedDate);
		$user->setLastLoginDate($lastLoginDate);
				
		return $user;
	}
	
	public static function createUser(User $user) {
		if(User::getUser($user->username) !== null) {
			throw new Exception("Error: User already exists!");
		}
		
		//Grab connection object
		$mysqli = Utility::getSQLConnection();
		
		$stmt = $mysqli->prepare("INSERT INTO users(username, password_hash, email, last_login_date) VALUES (?,?,?, NOW())");
		if ($mysqli->errno) {
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		//Bind parameters
		$stmt->bind_param("sss", $user->getUsername(), $user->getPasswordHash(), $user->getEmail());
		
		//Execute statement
		$stmt->execute();
		
		if ($mysqli->errno) {
			trigger_error($mysqli->error,E_USER_ERROR);
		}
		
		$stmt->close();
		unset($stmt);
		
		return "User created successfully.";
	}
	
	
	//Object Functions
	public function setPassword($password) {
		if(strlen($password) < 6) {
			throw new Exception("Error: Password is too short (6 chars min).");
		}
		if(strlen($password) > 72) {
			throw new Exception("Error: Password is too long (72 chars max).");
		}
		
		$hasher = Utility::getPasswordHasher();
		$hash = $hasher->HashPassword($password);
		$this->setPasswordHash($hash);
	}
	
	public function checkPassword($password) {
		$hasher = Utility::getPasswordHasher();
		return $hasher->CheckPassword($password, $this->passwordHash);
	}

	//Internal Data
	private $userId;
	private $username;
	private $passwordHash;
	private $email;
	private $joinedDate;
	private $lastLoginDate;
	
	
	//Getters/Setters
	public function getUserId(){
		return $this->userId;
	}
	public function setUserId($userId){
		$this->userId = (int)$userId;
	}

	public function getUsername(){
		return $this->username;
	}

	public function setUsername($username){
		if(strlen($username) === 0) {
			throw new Exception("Error: Username cannot be empty.");
		}
		if(strlen($username) > 16) {
			throw new Exception("Error: Username is too long (16 chars max).");
		}
		if(preg_match('/^(\w|-){1,16}$/', $username) === 0) {
			throw new Exception("Error: Invalid username (allowed characters: a-z,A-Z,0-9,-,_)");
		}
	
		$this->username = (string)$username;
	}

	public function getPasswordHash(){
		return $this->passwordHash;
	}
	
	public function setPasswordHash($passwordHash){
		$this->passwordHash = (string)$passwordHash;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		if(strlen($email) > 0 && !preg_match("/[^\\s]*@[a-z0-9.-]*/i", $email)) {
			throw new Exception("Error: Invalid email address.");
		}
		$this->email = (string)$email;
	}

	public function getJoinedDate(){
		return $this->joinedDate;
	}

	public function setJoinedDate($joinedDate){
		$this->joinedDate = $joinedDate;
	}

	public function getLastLoginDate(){
		return $this->lastLoginDate;
	}

	public function setLastLoginDate($lastLoginDate){
		$this->lastLoginDate = $lastLoginDate;
	}
}
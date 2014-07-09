<?php
require_once "library/User.php";

function CreateUser() {
	//Grab Input
	$username = $_REQUEST["username"];
	$password = $_REQUEST["password"];
	$passwordAgain = $_REQUEST["passwordagain"];
	$email = $_REQUEST["email"];

	//Pre-validate passwords
	if($password !== $passwordAgain) {
		throw new Exception("Error: Passwords do not match.");
	}
	
	$user = new User();
	$user->setUsername($username);
	$user->setPassword($password);
	$user->setEmail($email);
	
	return User::createUser($user);
}

try {
	$message = CreateUser();
	$result = "true";
} catch (Exception $e) {
	$message = $e->getMessage();
	$result = "false";
}
?>{
	"result":<?php echo $result; ?>,
	"message":"<?php echo $message; ?>"
}
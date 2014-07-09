<?php
require_once "library/User.php";

function serverLogin() {
	//Grab input
	$username = $_REQUEST["username"];
	$password = $_REQUEST["password"];
	
	$user = User::getUser($username);
	
	session_start();
	
	if($user === null || !$user->checkPassword($password)) {
		if(isset($_SESSION["username"])) {
			session_unset();
			session_destroy();
			session_write_close();
			session_regenerate_id(true);
		}
		throw new Exception("Error: Username/Password combo was incorrect.");
	} else {
		$_SESSION["username"] = $user->getUsername();
		return "Successful Login!";
	}
}

try {
	$message = serverLogin();
	$result = "true";
} catch (Exception $e) {
	$message = $e->getMessage();
	$result = "false";
}
?>{
	"result":<?php echo $result; ?>,
	"message":"<?php echo $message; ?>",
	"username":"<?php echo $_SESSION["username"]; ?>"
}
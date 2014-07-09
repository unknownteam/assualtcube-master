<?php
//require_once "library/User.php";

session_start();

if(isset($_SESSION["username"])) {
	session_unset();
	session_destroy();
	session_write_close();
	session_regenerate_id(true);
}

echo "Successfully logged out."
?>
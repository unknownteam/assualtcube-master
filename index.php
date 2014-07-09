<?php
$loggedIn = false;
$username = null;

session_start();
if(isset($_SESSION["username"])) {
	$username = $_SESSION["username"];
	$loggedIn = true;
}

?><!DOCTYPE HTML>
<html>
<head>
	<title>AssualtCube Master Server</title>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/style.css" rel="stylesheet" media="screen">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="span6"><img src="img/header.png" /></div>
			<div class="span6">
				<div class="login-box">
					<div id="logged-out" class="<?php if($loggedIn) { echo "hide"; }?>">
						<form id="login" action="loginuser.php" class="form-inline login-form">
							<input name="username" type="text" class="input-small" placeholder="Username" />
							<input name="password" type="password" class="input-small" placeholder="Password" />
							<label class="checkbox">
								<input name="rememberme" type="checkbox" /> Remember me
							</label>
							<button type="submit" name="submit" class="btn btn-primary">Sign in</button>
							<a href="#create-account-modal" role="button" class="btn" data-toggle="modal">Create Account</a>
						</form>
					</div>
					<div id="logged-in" class="<?php if(!$loggedIn) { echo "hide"; }?>">
						Logged in as <span id="username-text"><?php echo $username ?></span> 
						<a id="logout-link" href="logoutuser.php" class="pull-right">Logout</a>
					</div>
				</div>
				
				<form id="create-account" class="form-horizontal">
					<div id="create-account-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-create" aria-hidden="true">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h3 id="modal-create">Create Account</h3>
						</div>
						<div class="modal-body">
							<div class="control-group">
								<label class="control-label" for="inputEmail">Username</label>
								<div class="controls">
									<input type="text" name="username" id="create-username" tabindex="30" placeholder="Username" required><span class="help-inline"><span id="username-check" class="hide label"></span></span>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="create-password">Password</label>
								<div class="controls">
									<input type="password" name="password" id="create-password" tabindex="31" placeholder="Password" required><span class="help-inline"><span id="password-check" class="hide label"></span></span>
									<input type="password" name="passwordagain" id="create-password-again" tabindex="32" placeholder="Password Again" required><span class="help-inline"><span id="password-match" class="hide label"></span></span>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="create-email">Email</label>
								<div class="controls">
									<input type="text" name="email" id="create-email" tabindex="33" placeholder="Email (optional)">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button tabindex="40" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
							<button tabindex="35" class="btn btn-primary" type="submit">Create Account</button>
						</div>
					</div>
				</form>
				
				<!--This height needs to be slightly less than the header.png-->
				<div style="position:relative; height:130px;">
					<div style="position:absolute;right:0;bottom:0">
						<a href="" id="refresh-servers" class="btn">Refresh Servers</a>
					</div>
				</div>
			</div>
		</div>
		<span id="server-container">
<?php include 'servers.php' ?>
		</span>
	</div>
	<div class="wait"></div>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/script.js"></script>
</body>
</html>
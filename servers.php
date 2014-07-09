<?php 
require_once "library/Utility.php";
require_once "library/Server.php";

$loggedIn = false;
$username = null;

session_start();
if(isset($_SESSION["username"])) {
	$username = $_SESSION["username"];
	$loggedIn = true;
}

$servers = Server::getServerList();

$now = new DateTime();
		
		foreach($servers as $s) {
			$link = "md://" . htmlspecialchars($s->getAddress()) . ":" . htmlspecialchars($s->getPort()) . "/?";
			if($loggedIn) { $link .= "user=" . urlencode($username) . "&auth=" . urlencode(md5($s->getPrivateKey() . $username)) . "&"; }
			$link .= "serverPassword=" . $s->getPasswordProtected();
			$heartbeat = new DateTime($s->getLastHeartbeatDate());
			$dateDiff = $now->diff($heartbeat);
			$timeDiff = $now->getTimestamp() - $heartbeat->getTimestamp();
			$offline = false;
			if($timeDiff > 900) { $offline = true; }
		?>
		<div class="row">
			<div class="span12"><hr/></div>
		</div>
		<div class="row">
			<div class="server">
				<div class="container">
					<div class="row">
						<div class="span8">
							<h3><?php if(strtoupper($s->getPasswordProtected()) === "TRUE") 
							{
								?><img src="img/lock.png" alt="Server requires password." title="Server requires password."/><?php 
							} ?><span class="muted">[<?php echo htmlspecialchars($s->getGameMode()); ?>]</span> <?php 
							echo "<a href=\"" . $link . "\"";
							if($offline) {
								echo " class=\"offline-link\"";
							}
							echo ">";
							echo htmlspecialchars($s->getName()); 
							echo "</a>";
							?> (<?php echo htmlspecialchars($s->getUserCount() . '/' . $s->getMaxClients()); ?>)</h3>
							<?php echo htmlspecialchars($s->getMotd()); ?>
						</div>
						<div class="span4">
							<div class="well">
								<div class="pull-right"><a <?php echo "href=\"" . $link . "\" "; if($offline) { ?>class="btn">Offline<?php } else { ?>class="btn btn-success">Online!<?php } ?></a></div>
								<div>Address: <strong><?php echo htmlspecialchars($s->getAddress()); ?></strong></div>
								<div>Players: <strong><?php echo htmlspecialchars($s->getUserList()); ?></strong></div>
								<div>Version: <strong><?php echo htmlspecialchars($s->getVersion()); ?></strong></div>
								<div>Last Ping: <strong><?php echo Utility::formatDateDiff($dateDiff); ?> ago</strong></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><?php } ?>
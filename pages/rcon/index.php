<?php
	use \Nizarii\ARC;
	$connectionOkay = true;
	$connectionError = "";

	try{
		$settings = get_config();
		$rcon = new ARC($server->rcon_ip, decrypt_text($server->rcon_password, $settings["key"]), intval($server->rcon_port));
		$rcon->disconnect();
	}catch(Exception $e){
		$connectionOkay = false;
		$connectionError = '<i class="fa fa-times" aria-hidden="true"></i> ' . $e->getMessage();
	}
?>

<style>

</style>

<br><br><br><br><br><br>
<center>
	<?php
		if($connectionOkay){
			echo "<h4><font color='green'>Connection to server successful</font></h4>";
		}else{
			echo "<h4><font color='red'>$connectionError</font></h4>";
		}
	?>
	<br><br>
	<a href="/<?php echo $linksOffset ?>rcon/<?php echo $server->id ?>/bans"><button class="btn btn-primary"><i class="fa fa-list" aria-hidden="true"></i> Load Bans</button></a>
	<a href="/<?php echo $linksOffset ?>rcon/<?php echo $server->id ?>/players"><button class="btn btn-primary"><i class="fa fa-users" aria-hidden="true"></i> View Players</button></a>
	<br><br><br>
	<?php
		if(permissions::user_has_permission("restartrconserver")){
			echo "<h4>RCon Commands:</h4>";
	?>
		<button class="btn btn-primary" id="restartServer"><i class="fa fa-redo" aria-hidden="true"></i> Restart Server</button>
	<?php
		}
	?>
</center>

<script>
	$("#restartServer").click(function(){
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/rcon/restart.php", {serverid: "<?php echo $server->id ?>"}, false, function(html){
            essentials.message("Server succesfully restarted!", "success")
        })
	})
</script>

<?php
	$timeJoinedAlias = essentials::getAlias("timeJoinedAlias");
	$lastPlayedAlias = essentials::getAlias("lastPlayedAlias");
?>

<style>
	.aliasText{
		display: inline;
		transition: all 100ms;
	}
	.aliasText:hover{
		font-size: 16px;
		font-weight: 600;
	}
</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<?php include("navbar.php") ?>
        <div>
			<div class="row">
				<div class="col-md-4">
					<div class="panel panel-primary">
						<div class="panel-heading">Steam ID</div>
						<div class="panel-body">
							<h4><?php echo filterXSS($player->$playerIDAlias) ?></h4>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="panel panel-primary">
						<div class="panel-heading">Player GUID</div>
						<div class="panel-body">
							<b><h5 class="guid"></h5></b>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="panel panel-primary">
						<div class="panel-heading">Aliases</div>
						<div class="panel-body">
							<?php
								$aliasesArray = json_decode(str_replace("`", '"', preg_replace('(^"|"$)', "", $player->aliases)));
								foreach($aliasesArray as $key=>$value){
									$value = filterXSS($value);
									if($key >= count($aliasesArray)-1){
										echo "<b><h5 class='aliasText'>$value</h5></b>";
									}else{
										echo "<b><h5 class='aliasText'>$value</h5></b>, ";
									}
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="panel panel-info">
						<div class="panel-heading">Cash</div>
						<div class="panel-body">
							<h4>$<?php echo number_format($player->cash) ?></h4>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="panel panel-info">
						<div class="panel-heading">Bank</div>
						<div class="panel-body">
							<h4>$<?php echo number_format($player->bankacc) ?></h4>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="panel panel-info">
						<div class="panel-heading">Vehicles</div>
						<div class="panel-body">
							<h4><?php echo count(vehicles::get_by_owner($player->$playerIDAlias)) ?></h4>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="panel panel-success">
						<div class="panel-heading">Joined On</div>
						<div class="panel-body">
							<h4><?php echo timestamp_to_date($player->$timeJoinedAlias, true) ?></h4>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="panel panel-success">
						<div class="panel-heading">Last Seen</div>
						<div class="panel-body">
							<h4><?php echo timestamp_to_date($player->$lastPlayedAlias, true) ?> - been playing for <?php echo get_human_time_alt($player->$lastPlayedAlias - $player->$timeJoinedAlias) ?></h4>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="panel panel-success">
						<div class="panel-heading">Houses</div>
						<div class="panel-body">
							<h4><?php echo count(houses::get_by_owner($player->$playerIDAlias)) ?></h4>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<?php if($player->arrested){ ?>
					<div class="col-md-4 col-md-offset-4">
						<div class="panel panel-danger">
							<div class="panel-heading">Player is arrested!</div>
							<div class="panel-body" style="text-align: center;">
								<h4>Player was sentenced to jail for...</h4>
								<h4><?php echo $player->jailtime ?> minutes</h4>
								<h4>for '<?php echo $player->arrestreason ?>'</h4>
								<?php if(permissions::user_has_permission("releasefromjail")){ ?>
									<button class="btn btn-danger" id="releasejail">Release from jail</button>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
    </div>
</div>

<script>
	$(".guid").each(function(){
		$(this).html(essentials.steamIDToGUID("<?php echo $player->$playerIDAlias ?>"))
	})

	$("#releasejail").click(function(){
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/releasefromjail.php", {uid: <?php echo $player->uid ?>}, false, function(){
			location.reload()
		})
	})
</script>

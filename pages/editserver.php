<?php
	$settings = get_config();
?>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form id="submitForm">
					<a href="/<?php echo $linksOffset ?>servers"><button type="button" class="btn btn-sm btn-primary">Go Back</button></a>
					<?php
						if(permissions::user_has_permission("createservers")){
							echo '<button type="button" class="btn btn-danger" style="float:right;" id="deleteServer">Delete Server</button><br><br><br>';
						}
					?>
					<div class="input-group form-group">
						<span class="input-group-addon">Server Name</span>
						<input type="text" class="form-control" id="name" value="<?php echo $server->name ?>">
					</div>
					<h4>RCon Settings</h4>
					<div class="input-group form-group">
						<span class="input-group-addon">Use RCon?</span>
						<select class="form-control" id="use_rcon">
							<?php
								if($server->use_rcon){
									echo "
										<option selected>Yes</option>
										<option>No</option>
									";
								}else{
									echo "
										<option>Yes</option>
										<option selected>No</option>
									";
								}
							?>
						</select>
					</div>
					<div class="input-group form-group">
						<span class="input-group-addon">RCon Host IP</span>
						<input type="text" class="form-control" id="rcon_ip" value="<?php echo $server->rcon_ip ?>">
					</div>
					<div class="input-group form-group">
						<span class="input-group-addon">RCon Host Port</span>
						<input type="number" class="form-control" id="rcon_port" value="<?php echo $server->rcon_port ?>">
					</div>
					<div class="input-group form-group">
						<span class="input-group-addon">RCon Password</span>
						<input type="password" class="form-control" id="rcon_password" value="<?php echo decrypt_text($server->rcon_password, $settings["key"]) ?>">
					</div>
					<button type="submit" class="btn btn-primary" style="float:right;">Edit Server</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$("#submitForm").submit(function(){
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/editserver.php", {id: <?php echo $server->id ?>, name: $("#name").val(), use_rcon: $("#use_rcon").val(), rcon_ip: $("#rcon_ip").val(), rcon_port: $("#rcon_port").val(), rcon_password: $("#rcon_password").val()}, false, function(){
			$.notify({
				message: "Server edited!"
			},{
				type: "success"
			})
		})
		return false
	})

	$("#deleteServer").click(function(){
		if(confirm("Are you sure?")){
			essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/deleteserver.php", {id: <?php echo $server->id ?>}, false, function(){
				window.location = "/<?php echo $resourceLinksOffset ?>servers"
			})
		}
	})
</script>

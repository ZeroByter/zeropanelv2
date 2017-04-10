<br><br><br><br><br><br>
<div class="custom-panel">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<form id="submitForm">
				<h4>Create New Server</h4>
				<div class="input-group form-group">
					<span class="input-group-addon">Server Name</span>
					<input type="text" class="form-control" id="name" required>
				</div>
				<br><br>
				<h4>RCon Settings</h4>
				<div class="input-group form-group">
					<span class="input-group-addon">Use RCon?</span>
					<select class="form-control" id="use_rcon">
						<option>Yes</option>
						<option>No</option>
					</select>
				</div>
				<div class="input-group form-group">
					<span class="input-group-addon">RCon Host IP</span>
					<input type="text" class="form-control" id="rcon_ip">
				</div>
				<div class="input-group form-group">
					<span class="input-group-addon">RCon Host Port</span>
					<input type="number" class="form-control" id="rcon_port">
				</div>
				<div class="input-group form-group">
					<span class="input-group-addon">RCon Password</span>
					<input type="password" class="form-control" id="rcon_password">
				</div>
				<button type="submit" class="btn btn-primary" style="float:right;">Create Server</button>
			</form>
		</div>
	</div>
</div>

<script>
	$("#submitForm").submit(function(){
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/createserver.php", {name: $("#name").val(), use_rcon: $("#use_rcon").val(), rcon_ip: $("#rcon_ip").val(), rcon_port: $("#rcon_port").val(), rcon_password: $("#rcon_password").val()}, false, function(html){
			window.location = "/<?php echo $linksOffset ?>servers/" + html.split("/")[1];
		})
		return false
	})
</script>

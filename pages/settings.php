<?php
	$key = $settings["key"];
	$mysqlIP = decrypt_text($settings["mysql"]["ip"], $key);
	$mysqlUsername = decrypt_text($settings["mysql"]["username"], $key);
	$mysqlPassword = decrypt_text($settings["mysql"]["password"], $key);
	$mysqlDBName = decrypt_text($settings["mysql"]["dbname"], $key);
?>

<style>
    .itemRow[data-banned="1"]{
        color: red;
    }
	[type="submit"]{
		float: right;
	}
</style>

<br><br><br><br><br><br>
<div class="custom-panel">
	<h4 style="float:left;margin-left:20px;"><i class="fa fa-wrench" aria-hidden="true"></i> Settings</h4>
	<br><br>
	<hr class="hidden-xs">
	<div class="row">
		<div class="col-md-6">
			<h3>MySQL Settings</h3>
			<form id="mysqlForm">
				<div class="input-group form-group">
					<span class="input-group-addon">MySQL Host IP</span>
					<input type="text" class="form-control" id="mysqlIP" value="<?php echo $mysqlIP ?>" required>
				</div>
				<div class="input-group form-group">
					<span class="input-group-addon">MySQL Username</span>
					<input type="text" class="form-control" id="mysqlUsername" value="<?php echo $mysqlUsername ?>" required>
				</div>
				<div class="input-group form-group">
					<span class="input-group-addon">MySQL Password</span>
					<input type="password" class="form-control" id="mysqlPassword" value="<?php echo $mysqlPassword ?>" required>
				</div>
				<div class="input-group form-group">
					<span class="input-group-addon">MySQL Database Name</span>
					<input type="text" class="form-control" id="mysqlDBName" value="<?php echo $mysqlDBName ?>" required>
				</div>
				<button type="submit" class="btn btn-primary"><i class="fa fa-edit" aria-hidden="true"></i> Edit MySQL Settings</button>
			</form>
		</div>
		<div class="col-md-6">
			<h3>Other Settings</h3>
			<form id="otherForm">
				<div class="input-group form-group">
					<span class="input-group-addon">Community Name</span>
					<input type="text" class="form-control" id="communityName" value="<?php echo $settings["communityName"] ?>" required>
				</div>
				<div class="input-group form-group">
					<span class="input-group-addon">Permissions Update Interval</span>
					<input type="number" class="form-control" id="permUpdateInt" value="<?php echo $settings["permissionsUpdateInterval"] ?>" required>
				</div>
				<div class="well well-sm">
					<h4>Max Access Levels</h4>
					<div class="input-group form-group">
						<span class="input-group-addon">Cop Level</span>
						<input type="number" class="form-control" id="maxCopLevel" value="<?php echo $settings["maxlevels"]["coplevel"] ?>" required>
					</div>
					<div class="input-group form-group">
						<span class="input-group-addon">Medic Level</span>
						<input type="number" class="form-control" id="maxMedLevel" value="<?php echo $settings["maxlevels"]["mediclevel"] ?>" required>
					</div>
					<div class="input-group form-group">
						<span class="input-group-addon">Donor Level</span>
						<input type="number" class="form-control" id="maxDonorLevel" value="<?php echo $settings["maxlevels"]["donorlevel"] ?>" required>
					</div>
				</div>
				<div class="well well-sm">
					<h4>URL Settings <font color="red" data-toggle="tooltip" title="Do not touch this unless you know this is for and what you are doing! This could mess up access to your panel if done wrong!">*</font></h4>
					<div class="input-group form-group">
						<span class="input-group-addon">URL Offset</span>
						<input type="text" class="form-control" id="linksOffset" value="<?php echo $settings["linksOffset"] ?>">
					</div>
					<div class="input-group form-group">
						<span class="input-group-addon">Resources URL Offset</span>
						<input type="text" class="form-control" id="resourceLinksOffset" value="<?php echo $settings["resourceLinksOffset"] ?>">
					</div>
				</div>
				<button type="submit" class="btn btn-primary"><i class="fa fa-edit" aria-hidden="true"></i> Edit Panel Settings</button>
			</form>
		</div>
	</div>
</div>

<script>
	$("[data-toggle='tooltip']").tooltip()

	$("#mysqlForm").submit(function(){
		if(confirm("You are about to change the MySQL settings of your panel! Configuring the wrong MySQL settings will result in the panel ceasing to work and you will maybe have to reinstall the panel to fix the problem. Are you sure?")){
			essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/editconfig/editmysql.php", {
				mysqlIP: $("#mysqlIP").val(),
				mysqlUsername: $("#mysqlUsername").val(),
				mysqlPassword: $("#mysqlPassword").val(),
				mysqlDBName: $("#mysqlDBName").val(),
			}, false, function(){
				location.reload()
			})
		}
		return false
	})

	$("#otherForm").submit(function(){
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/editconfig/editsettings.php", {
			communityName: $("#communityName").val(),
			permUpdateInt: $("#permUpdateInt").val(),
			maxCopLevel: $("#maxCopLevel").val(),
			maxMedLevel: $("#maxMedLevel").val(),
			maxDonorLevel: $("#maxDonorLevel").val(),
			linksOffset: $("#linksOffset").val(),
			resourceLinksOffset: $("#resourceLinksOffset").val(),
		}, false, function(){
			location.reload()
		})
		return false
	})
</script>

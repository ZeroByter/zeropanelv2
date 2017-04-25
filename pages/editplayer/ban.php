<?php

?>

<style>

</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<?php include("navbar.php") ?>
		<br><br>
        <div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form id="submitForm">
					<div class="input-group form-group">
						<span class="input-group-addon">Ban Reason</span>
						<input type="text" class="form-control" id="banReason" required>
					</div>
					<div class="input-group form-group">
						<span class="input-group-addon">Ban Notes</span>
						<input type="text" class="form-control" id="banNotes" required>
					</div>
					<div class="input-group form-group">
						<span class="input-group-addon">Ban From Server...</span>
						<select class="form-control" id="banServer" required>
							<option disabled selected>Select a server</option>
							<?php
								foreach(servers::get_all_real() as $value){
									echo "<option data-id='$value->id'>$value->name</option>";
								}
							?>
						</select>
					</div>
					<div class="input-group form-group">
						<span class="input-group-addon">Ban Time</span>
						<input type="number" class="form-control" id="banTime" value="1" required>
						<select class="form-control" id="banTimeType">
							<option>Minutes</option>
							<option>Hours</option>
							<option>Days</option>
							<option>Weeks</option>
							<option>Months</option>
							<option>Years</option>
							<option>Permanent</option>
						</select>
					</div>
					<button type="submit" class="btn btn-danger" style="width:100%;"><i class="fa fa-times" aria-hidden="true"></i> Issue Ban</button>
				</form>
			</div>
		</div>
    </div>
</div>

<script>
	var playerID = <?php echo $player->uid ?>
	var serverid = 0
	var bantime = 1
	var bantype = "Minutes"

	function getTime(){
		if(bantype == "Minutes"){
			return bantime
		}
		if(bantype == "Hours"){
			return bantime * 60
		}
		if(bantype == "Days"){
			return bantime * 60 * 24
		}
		if(bantype == "Weeks"){
			return bantime * 60 * 24 * 7
		}
		if(bantype == "Months"){
			return bantime * 60 * 24 * 7 * 4
		}
		if(bantype == "Years"){
			return bantime * 60 * 24 * 7 * 4 * 12
		}
		if(bantype == "Permanent"){
			return 0
		}
	}

    $("#banTimeType").change(function(){
		bantype = $(this).val()
		if(bantype == "Permanent"){
			$("#banTime").attr("disabled", "")
		}else{
			$("#banTime").removeAttr("disabled")
		}
	})
	$("#banServer").change(function(){
		serverid = $("option:selected", this).data("id")
	})
	$("#banTime").bind("keyup change", function(){
		bantime = $(this).val()
	})

	$("#submitForm").submit(function(){
		if(serverid == 0){
			essentials.message("Please choose a server", "danger")
			return false
		}

		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/rcon/ban.php", {serverid: serverid, playerid: playerID, guid: essentials.steamIDToGUID("<?php echo $player->playerid ?>"), reason: $("#banReason").val(), notes: $("#banNotes").val(), time: getTime()}, false, function(){
			window.location = "/players/" + <?php echo $player->playerid ?>
		})
		return false
	})
</script>

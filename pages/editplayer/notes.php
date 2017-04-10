<?php
	$notes = playernotes::get_all($player->uid);
?>

<style>

</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<?php include("navbar.php") ?>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
				<?php
					if($selfMoneyChanged){
						echo "<center><h4><div class='label label-danger' data-toggle='tooltip' title='$selfMoneyAdmin has manipulated his own money balance on this player! Possible abuse of staff panel!'>Staff Money Manipulation Warning!</div></h4></center><br>";
					}
				?>
				<h4>Money Manipulation History:</h4>
                <table class="table">
					<thead>
						<tr>
							<th>Staff Member</th>
							<th>Money Given</th>
							<th>Time</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($moneyLogs as $value){
								echo "<tr>";
								echo "<td>" . filterXSS($value->admins_name) . "($value->admins_playerid) </td>";
								if($value->money_given > 0){
									echo "<td><font color='green'>$".number_format($value->money_given)."</font></td>";
								}else{
									echo "<td><font color='red'>$".number_format($value->money_given)."</font></td>";
								}
								echo "<td>".timestamp_to_date($value->time, true)."</td>";
								echo "</tr>";
							}
						?>
					</tbody>
				</table>
				<br><br>
				<h4>Player Notes:</h4>
				<?php if(permissions::user_has_permission("addnotes")){ ?>
					<div class="input-group">
						<span class="input-group-addon">Note</span>
						<input type="text" class="form-control" id="addNoteInput">
						<span class="input-group-btn">
							<button class="btn btn-primary" id="addNoteBtn">Post</button>
						</span>
					</div>
				<?php } ?>
                <table class="table">
					<thead>
						<tr>
							<th>Staff Name</th>
							<th>Player Name</th>
							<th>Text</th>
							<th>Time</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($notes as $value){
								echo "<tr>";
								echo "<td>".filterXSS($value->admin)."</td>";
								echo "<td>".filterXSS($value->player)."</td>";
								echo "<td>$value->notes</td>";
								echo "<td>".timestamp_to_date($value->time, true)."</td>";
								echo "</tr>";
							}
						?>
					</tbody>
				</table>
            </div>
        </div>
    </div>
</div>

<script>
	$('[data-toggle="tooltip"]').tooltip()
	var playerID = <?php echo $player->uid ?>

	$("#addNoteBtn").click(function(){
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/addnote.php", {id: playerID, message: $("#addNoteInput").val()}, false, function(){
			location.reload()
		})
	})
</script>

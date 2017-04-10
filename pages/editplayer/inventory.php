<?php

?>

<style>
	.form-control{
		resize: vertical;
	}
	.submitBtn{
		margin: 10px 0px;
		float: right;
	}
</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<?php include("navbar.php") ?>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
				<h4>Civilian Gear</h4>
				<?php
					if($player->civ_gear == '"[]"'){
						echo "<font color='grey'>Civilian gear is empty</font>";
					}else{
						echo "<textarea class='form-control' rows='6' id='civ_gear'>$player->civ_gear</textarea>";
						echo "<button class='btn btn-primary submitBtn' data-for='#civ_gear'>Change Gear</button>";
					}
				?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
				<h4>Medic Gear</h4>
				<?php
					if($player->med_gear == '"[]"'){
						echo "<font color='grey'>Medic gear is empty</font>";
					}else{
						echo "<textarea class='form-control' rows='6' id='med_gear'>$player->med_gear</textarea>";
						echo "<button class='btn btn-primary submitBtn' data-for='#med_gear'>Change Gear</button>";
					}
				?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
				<h4>Cop Gear</h4>
				<?php
					if($player->cop_gear == '"[]"'){
						echo "<font color='grey'>Cop gear is empty</font>";
					}else{
						echo "<textarea class='form-control' rows='6' id='cop_gear'>$player->cop_gear</textarea>";
						echo "<button class='btn btn-primary submitBtn' data-for='#cop_gear'>Change Gear</button>";
					}
				?>
            </div>
        </div>
		<br>
    </div>
</div>

<script>
	var playerID = <?php echo $player->uid ?>

    $(".submitBtn").click(function(){
		var gear = $($(this).data("for")).val()
		var type = ($(this).data("for")).replace("#", "")
		console.log($($(this).data("for")))

		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/changegear.php", {uid: playerID, type: type, gear: gear}, true, function(){
			$.notify({
				message: "Gear updated",
			},{
				type: "success",
			})
		})
	})
</script>

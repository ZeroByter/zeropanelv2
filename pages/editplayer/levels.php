<?php

?>

<style>

</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<?php include("navbar.php") ?>
        <center style="width:50%;margin:0px auto;">
            <div class="input-group form-group">
                <span class="input-group-addon">Cop Level</span>
                <input type="number" class="form-control" id="coplevel" value="<?php echo $player->coplevel ?>" max="<?php echo $player->coplevel ?>">
            </div>
            <div class="input-group form-group">
                <span class="input-group-addon">Medic Level</span>
                <input type="number" class="form-control" id="mediclevel" value="<?php echo $player->mediclevel ?>" max="<?php echo $player->mediclevel ?>">
            </div>
            <div class="input-group form-group">
                <span class="input-group-addon">Donor Level</span>
                <input type="number" class="form-control" id="donatorlvl" value="<?php echo $player->donatorlvl ?>" max="<?php echo $player->donatorlvl ?>">
            </div>
        </center>
    </div>
</div>

<script>
	var playerID = <?php echo $player->uid ?>

    $("#coplevel, #mediclevel, #donatorlvl").change(function(){
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/changelevel.php", {uid: playerID, type: $(this).attr("id"), level: $(this).val()}, false, function(){
			$.notify({
				message: "Player level updated!",
			},{
				type: "success",
			})
		})
	})
</script>

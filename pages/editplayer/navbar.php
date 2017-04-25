<h4 style="float:left;margin-left:20px;"><i class="fa fa-user" aria-hidden="true"></i> <?php echo filterXSS("$player->name ($player->playerid)") ?></h4>
<br><br>
<hr>
<div style="margin:10px;">
	<font color="grey">User has <?php echo count(playernotes::get_all($player->uid)) ?> notes<br></font>
	<?php
		$moneyLogs = moneylogs::get_all_by_player($player->uid);
		$selfMoneyChanged = false;
		$selfMoneyAdmin = "";
		foreach($moneyLogs as $value){
			if($value->admins_playerid == $player->playerid){
				$selfMoneyAdmin = filterXSS("$value->admins_name ($value->admins_playerid)");
				$selfMoneyChanged = true;
			}
		}
		if($selfMoneyChanged){
			echo "<font color='red' data-toggle='tooltip' title='$selfMoneyAdmin has manipulated his own money balance on this player! Possible abuse of staff panel!'>Money Alert!</font><br>";
		}

		$banCount = count(bans::get_all_active($player->playerid));
		if($banCount > 0){
			echo "<font color='red'>User has $banCount active bans.</font><br>";
		}
	?>
</div>
<ul class="nav nav-tabs">
    <?php
        echo "<li class='$mainActive'><a href='/".$linksOffset."players/$player->playerid'>Main</a></li>";
        if(permissions::user_has_permission("editlevels")){
            echo "<li class='$levelsActive'><a href='/".$linksOffset."players/$player->playerid/levels'>Levels</a></li>";
        }
        if(permissions::user_has_permission("editmoneybank") || permissions::user_has_permission("editmoneycash")){
            echo "<li class='$moneyActive'><a href='/".$linksOffset."players/$player->playerid/money'>Money</a></li>";
        }
        if(permissions::user_has_permission("editlicenses")){
            echo "<li class='$licensesActive'><a href='/".$linksOffset."players/$player->playerid/licenses'>Licenses</a></li>";
        }
        if(permissions::user_has_permission("editinventory")){
            echo "<li class='$inventoriesActive'><a href='/".$linksOffset."players/$player->playerid/inventory'>Inventories</a></li>";
        }
        echo "<li class='$vehiclesActive'><a href='/".$linksOffset."players/$player->playerid/vehicles'>Vehicles</a></li>";
        echo "<li class='$housesActive'><a href='/".$linksOffset."players/$player->playerid/houses'>Houses</a></li>";
        echo "<li class='$notesActive'><a href='/".$linksOffset."players/$player->playerid/notes'>Notes</a></li>";
        if(permissions::user_has_permission("banplayer")){
            echo "<li class='$bansActive'><a href='/".$linksOffset."players/$player->playerid/ban'><font color='red'>Ban</a></font></li>";
        }
    ?>
</ul>
<br>

<script>
	$('[data-toggle="tooltip"]').tooltip()
</script>

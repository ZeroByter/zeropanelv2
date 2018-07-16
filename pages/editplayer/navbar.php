<h4 style="float:left;margin-left:20px;"><i class="fa fa-user" aria-hidden="true"></i> <?php echo filterXSS("$player->name ({$player->$playerIDAlias})") ?></h4>
<br><br>
<hr>
<div style="margin:10px;">
	<font color="grey">User has <?php echo count(playernotes::get_all($player->uid)) ?> notes<br></font>
	<?php
		$moneyLogs = moneylogs::get_all_by_player($player->uid);
		$selfMoneyChanged = false;
		$selfMoneyAdmin = "";
		foreach($moneyLogs as $value){
			if($value->admins_playerid == $player->$playerIDAlias){
				$selfMoneyAdmin = filterXSS("$value->admins_name ($value->admins_playerid)");
				$selfMoneyChanged = true;
			}
		}
		if($selfMoneyChanged){
			echo "<font color='red' data-toggle='tooltip' title='$selfMoneyAdmin has manipulated his own money balance on this player! Possible abuse of staff panel!'>Money Alert!</font><br>";
		}

		$banCount = count(bans::get_all_active($player->$playerIDAlias));
		if($banCount > 0){
			echo "<font color='red'>User has $banCount active bans.</font><br>";
		}
	?>
</div>
<ul class="nav nav-tabs">
    <?php
        echo "<li class='$mainActive'><a href='/".$linksOffset."players/{$player->$playerIDAlias}'>Main</a></li>";
        if(permissions::user_has_permission("editlevels")){
            echo "<li class='$levelsActive'><a href='/".$linksOffset."players/{$player->$playerIDAlias}/levels'>Levels</a></li>";
        }
        if(permissions::user_has_permission("editmoneybank") || permissions::user_has_permission("editmoneycash")){
            echo "<li class='$moneyActive'><a href='/".$linksOffset."players/{$player->$playerIDAlias}/money'>Money</a></li>";
        }
        if(permissions::user_has_permission("editlicenses")){
            echo "<li class='$licensesActive'><a href='/".$linksOffset."players/{$player->$playerIDAlias}/licenses'>Licenses</a></li>";
        }
        if(permissions::user_has_permission("editinventory")){
            echo "<li class='$inventoriesActive'><a href='/".$linksOffset."players/{$player->$playerIDAlias}/inventory'>Inventories</a></li>";
        }
        echo "<li class='$vehiclesActive'><a href='/".$linksOffset."players/{$player->$playerIDAlias}/vehicles'>Vehicles</a></li>";
        echo "<li class='$housesActive'><a href='/".$linksOffset."players/{$player->$playerIDAlias}/houses'>Houses</a></li>";
        echo "<li class='$notesActive'><a href='/".$linksOffset."players/{$player->$playerIDAlias}/notes'>Notes</a></li>";
        if(permissions::user_has_permission("banplayer")){
            echo "<li class='$bansActive'><a href='/".$linksOffset."players/{$player->$playerIDAlias}/ban'><font color='red'>Ban</a></font></li>";
        }
    ?>
</ul>
<br>

<script>
	$('[data-toggle="tooltip"]').tooltip()
</script>

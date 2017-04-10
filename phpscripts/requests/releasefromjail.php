<?php
    include("../fillin/scripts.php");

    if(isset($_POST["uid"])){
        if(permissions::user_has_permission("releasefromjail")){
			$player = players::get_by_id($_POST["uid"]);
            players::release_from_jail($_POST["uid"]);
            logs::add_log("players", "$1 released [player/$player->name($player->playerid)] from jail, he prevented a $player->jailtime sentence", 25);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

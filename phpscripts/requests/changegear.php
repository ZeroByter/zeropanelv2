<?php
    include("../fillin/scripts.php");

    if(isset($_POST["uid"]) && isset($_POST["type"]) && isset($_POST["gear"])){
        if(permissions::user_has_permission("editinventory")){
            players::changeGear($_POST["uid"], $_POST["type"], $_POST["gear"]);
			$player = players::get_by_id($_POST["uid"]);
			logs::add_log("player", "$1 changed [player/$player->name] gear", 15);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

<?php
    include("../fillin/scripts.php");

    if(isset($_POST["uid"]) && isset($_POST["type"]) && isset($_POST["level"])){
        if(permissions::user_has_permission("editlevels")){
            $settings = get_config();
            if($_POST["level"] > $settings["maxlevels"][$_POST["type"]]) $_POST["level"] = $settings["maxlevels"][$_POST["type"]];
            if($_POST["level"] < 0) $_POST["level"] = 0;
            players::changeLevel($_POST["uid"], $_POST["type"], $_POST["level"]);
			$player = players::get_by_id($_POST["uid"]);
			logs::add_log("player", "$1 changed [player/$player->name] {$_POST["type"]} to {$_POST["level"]}", 15);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

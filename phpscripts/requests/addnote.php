<?php
    include("../fillin/scripts.php");

    if(isset($_POST["id"]) && isset($_POST["message"])){
        if(permissions::user_has_permission("addnotes")){
			$player = players::get_by_id($_POST["id"]);
			logs::add_log("player", "$1 added a note to player $player->name", 1);
            playernotes::create($_POST["id"], $_POST["message"]);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

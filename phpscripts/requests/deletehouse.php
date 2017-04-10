<?php
    include("../fillin/scripts.php");

    if(isset($_POST["id"])){
        if(permissions::user_has_permission("deletehouses")){
			$house = houses::get_by_id($_POST["id"]);
			$owner = players::get_by_steamid($house->pid);
			logs::add_log("vehicle", "$1 deleted a house that belonged to [player/$owner->name]", 10);
            houses::delete($_POST["id"]);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

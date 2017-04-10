<?php
    include("../fillin/scripts.php");

    if(isset($_POST["id"])){
        if(permissions::user_has_permission("deletevehicle")){
			$vehicle = vehicles::get_by_id($_POST["id"]);
			$owner = players::get_by_steamid($vehicle->pid);
			logs::add_log("vehicle", "$1 deleted vehicle [$vehicle->classname] that belonged to [player/$owner->name]", 10);
            vehicles::delete($_POST["id"]);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

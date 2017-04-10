<?php
    include("../fillin/scripts.php");

    if(isset($_POST["id"]) && isset($_POST["inventory"])){
        if(permissions::user_has_permission("editvehinventory")){
			$vehicle = vehicles::get_by_id($_POST["id"]);
			$owner = players::get_by_steamid($vehicle->pid);
			logs::add_log("vehicle", "$1 changed the inventory of [vehicle/$vehicle->classname] that belonged to [player/$owner->name]", 15);
            vehicles::changeInventory($_POST["id"], $_POST["inventory"]);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

<?php
    include("../fillin/scripts.php");

    if(isset($_POST["id"]) && isset($_POST["inventory"])){
        if(permissions::user_has_permission("edithousesinventory")){
			$house = houses::get_by_id($_POST["id"]);
			$owner = players::get_by_steamid($house->pid);
			logs::add_log("vehicle", "$1 changed the inventory of a house that belonged to [player/$owner->name]", 15);
            houses::changeInventory($_POST["id"], $_POST["inventory"]);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

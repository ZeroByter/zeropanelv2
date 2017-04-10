<?php
    include("../fillin/scripts.php");

    if(isset($_POST["id"])){
        if(permissions::user_has_permission("editvehicles")){
            vehicles::store($_POST["id"]);
			$vehicle = vehicles::get_by_id($_POST["id"]);
			logs::add_log("vehicle", "$1 stored [vehicle/$vehicle->classname]", 2);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

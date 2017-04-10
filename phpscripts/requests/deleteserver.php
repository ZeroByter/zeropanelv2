<?php
    include("../fillin/scripts.php");

    if(isset($_POST["id"])){
        if(permissions::user_has_permission("createservers")){
			$server = servers::get_by_id($_POST["id"]);
			logs::add_log("server", "$1 deleted [server/$server->name]", 80);
            servers::delete($_POST["id"]);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

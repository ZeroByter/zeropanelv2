<?php
    include("../fillin/scripts.php");

    if(isset($_POST["uid"]) && isset($_POST["type"]) && isset($_POST["licenseName"])){
        if(permissions::user_has_permission("editlicenses")){
            $licenseName = $_POST["licenseName"];
            $licenses = players::getLicenses($_POST["uid"], $_POST["type"]);
			$player = players::get_by_id($_POST["uid"]);
            if(strpos($licenses, "$licenseName`,0") !== false){
                $licenses = preg_replace("/$licenseName`,0/", "$licenseName`,1", $licenses);
				logs::add_log("player", "$1 gave $player->name license $licenseName", 2);
            }else{
                $licenses = preg_replace("/$licenseName`,1/", "$licenseName`,0", $licenses);
				logs::add_log("player", "$1 removed $player->name license $licenseName", 2);
            }
            players::changeLicenses($_POST["uid"], $_POST["type"], $licenses);

            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

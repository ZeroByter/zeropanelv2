<?php
    include("../fillin/scripts.php");

    if(isset($_POST["id"]) && isset($_POST["name"]) && isset($_POST["use_rcon"]) && isset($_POST["rcon_ip"]) && isset($_POST["rcon_port"]) && isset($_POST["rcon_password"])){
        if(permissions::user_has_permission("editservers")){
			$server = servers::get_by_id($_POST["id"]);
            if($_POST["use_rcon"] == "Yes"){
                $_POST["use_rcon"] = true;
            }else{
                $_POST["use_rcon"] = false;
            }
            servers::editRCon($_POST["id"], $_POST["use_rcon"], $_POST["rcon_ip"], $_POST["rcon_port"], $_POST["rcon_password"]);
            servers::editName($_POST["id"], $_POST["name"]);
			logs::add_log("server", "$1 editted [server/$server->name]", 25);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

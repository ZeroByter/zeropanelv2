<?php
    include("../fillin/scripts.php");

    if(isset($_POST["name"]) && isset($_POST["use_rcon"]) && isset($_POST["rcon_ip"]) && isset($_POST["rcon_port"]) && isset($_POST["rcon_password"])){
        if(permissions::user_has_permission("createservers")){
			logs::add_log("server", "$1 created [server/{$_POST["name"]}]", 50);
            if($_POST["use_rcon"] == "Yes"){
                $_POST["use_rcon"] = true;
            }else{
                $_POST["use_rcon"] = false;
            }
            $id = servers::create($_POST["name"], $_POST["use_rcon"], $_POST["rcon_ip"], $_POST["rcon_port"], $_POST["rcon_password"]);
            echo "success/$id";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

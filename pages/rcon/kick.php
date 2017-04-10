<?php
    include("../../fillin/scripts.php");

    use \Nizarii\ARC;

    if(isset($_POST["serverid"]) && isset($_POST["playerid"]) && $_POST["playerid"] != "undefined" && isset($_POST["message"])){
        if(permissions::user_has_permission("kickrconplayers")){
            $currAccount = accounts::get_current_account();

            try{
        		$settings = get_config();
                $server = servers::get_by_id($_POST["serverid"]);
        		$rcon = new ARC($server->rcon_ip, decrypt_text($server->rcon_password, $settings["key"]), intval($server->rcon_port));
                if($_POST["message"] == ""){
                    $_POST["message"] = "Kicked by $currAccount->username";
                }
                $rcon->kickPlayer(intval($_POST["playerid"]), "($currAccount->username) " . $_POST["message"]);
                $rcon->disconnect();

                logs::add_log("rcon", "$1 kicked player [{$_POST["playerid"]}] for [{$_POST["message"]}]", 60);
                echo "success/($currAccount->username) " . $_POST["message"];
        	}catch(Exception $e){
        		echo $e->getMessage();
        	}
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

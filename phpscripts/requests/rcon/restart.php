<?php
    include("../../fillin/scripts.php");

    use \Nizarii\ARC;

    if(isset($_POST["serverid"])){
        if(permissions::user_has_permission("restartrconserver")){
            $currAccount = accounts::get_current_account();

            try{
        		$settings = get_config();
                $server = servers::get_by_id($_POST["serverid"]);
        		$rcon = new ARC($server->rcon_ip, decrypt_text($server->rcon_password, $settings["key"]), intval($server->rcon_port));
                if($_POST["message"] == ""){
                    $_POST["message"] = "Kicked by $currAccount->username";
                }
                $rcon->command("restartserver");
                $rcon->disconnect();

                logs::add_log("rcon", "$1 restarted the server [{$_POST["serverid"]}]", 70);
                echo "success";
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

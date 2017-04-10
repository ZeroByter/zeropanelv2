<?php
    include("../../fillin/scripts.php");

    use \Nizarii\ARC;

    if(isset($_POST["serverid"]) && isset($_POST["banid"])){
        if(permissions::user_has_permission("removerconbans")){
            $currAccount = accounts::get_current_account();

            try{
        		$settings = get_config();
                $server = servers::get_by_id($_POST["serverid"]);
        		$rcon = new ARC($server->rcon_ip, decrypt_text($server->rcon_password, $settings["key"]), intval($server->rcon_port));
                $rcon->removeBan(intval($_POST["banid"]));
                $rcon->disconnect();
				bans::inactive($_POST["banid"]);

                logs::add_log("rcon", "$1 removed rcon ban [{$_POST["banid"]}]", 60);
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

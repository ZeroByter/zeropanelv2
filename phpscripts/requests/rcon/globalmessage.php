<?php
    include("../../fillin/scripts.php");

    use \Nizarii\ARC;

    if(isset($_POST["serverid"]) && isset($_POST["message"])){
        if(permissions::user_has_permission("sayrconglobal")){
            $currAccount = accounts::get_current_account();

            try{
        		$settings = get_config();
                $server = servers::get_by_id($_POST["serverid"]);
        		$rcon = new ARC($server->rcon_ip, decrypt_text($server->rcon_password, $settings["key"]), intval($server->rcon_port));
                $rcon->sayGlobal("($currAccount->username) " . $_POST["message"]);
                $rcon->disconnect();

                logs::add_log("rcon", "$1 sent a global rcon message [{$_POST["message"]}]", 40);
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

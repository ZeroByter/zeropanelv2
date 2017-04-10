<?php
    include("../../fillin/scripts.php");

    use \Nizarii\ARC;

    if(isset($_POST["serverid"]) && isset($_POST["playerid"]) && isset($_POST["guid"]) && isset($_POST["reason"]) && isset($_POST["notes"]) && isset($_POST["time"])){
        if(permissions::user_has_permission("banplayer")){
            $currAccount = accounts::get_current_account();
			$player = players::get_by_id($_POST["playerid"]);

			if($_POST["reason"] == ""){ echo "Reason can not be empty!"; return; }
            foreach(bans::get_all_active($player->playerid) as $value){
				echo "User is already banned!";
                return;
    		}

            try{
        		$settings = get_config();
                $server = servers::get_by_id($_POST["serverid"]);
                bans::addBanCount();
                $banCount = bans::getBanCount();
        		$rcon = new ARC($server->rcon_ip, decrypt_text($server->rcon_password, $settings["key"]), intval($server->rcon_port));
                $rcon->addBan($_POST["guid"], "$player->name | Admin Ban | #$banCount | $currAccount->username", intval($_POST["time"]));
                $rcon->loadBans();
                $rcon->disconnect();
				bans::create($player->playerid, $_POST["guid"], $_POST["time"], $_POST["reason"], $_POST["notes"]);

                logs::add_log("rcon", "$1 banned player [{$_POST["guid"]}] for [reason/{$_POST["reason"]}] for [minutes/{$_POST["time"]}]", 60);
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

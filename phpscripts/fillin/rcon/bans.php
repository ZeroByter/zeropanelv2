<?php
    include("../scripts.php");

	use \Nizarii\ARC;

	try{
		$settings = get_config();
        $server = servers::get_by_id($_GET["id"]);
		$rcon = new ARC($server->rcon_ip, decrypt_text($server->rcon_password, $settings["key"]), intval($server->rcon_port));
        $bans = $rcon->getBansArray();
        $rcon->disconnect();

        foreach($bans as $value){
            $banid = "";
            if(preg_match("/#\d+/", $value["3"])){
                preg_match("/#\d+/", $value["3"], $banid);
                $banid = preg_replace("/#/", "", $banid);
                $banid = $banid[0];
            }
            $steamID = "";
            $name = "";
            $reason = $value["3"];
            $notes = "";
            $timeBanned = "";
            if($value["2"] == "perm"){
                $expiresTime = "Permanent";
            }else{
                $expiresTime = get_human_time_alt($value["2"] * 60);
            }
            $bannedBy = "";
            $mysqlBan = bans::get_by_banid($banid);
            if(isset($mysqlBan->id)){
                $name = $mysqlBan->name;
                $reason = $mysqlBan->reason;
                $notes = $mysqlBan->notes;
                $staff = accounts::get_by_id($mysqlBan->staff);
                $steamID = $mysqlBan->steamid;
                $timeBanned = timestamp_to_date($mysqlBan->created, true);
                $bannedBy = $staff->username;
            }

            echo "
                <tr data-id='$banid' data-rconid='{$value["0"]}' data-steamid='$steamID' class='playerRow'>
                    <td>".filterXSS($value["1"])."</td>
                    <td>".filterXSS($name)."</td>
                    <td>$steamID</td>
                    <td>".filterXSS($notes)."</td>
                    <td>$reason</td>
                    <td>$timeBanned</td>
                    <td>$expiresTime</td>
                    <td>".filterXSS($bannedBy)."</td>
                </tr>
            ";
        }
        if(count($bans) < 1){
            echo "<center><h4>There are no bans.</h4></center>";
        }
	}catch(Exception $e){
        echo "<center><h4>". $e->getMessage() ."</h4></center>";
	}
?>

<script>
    $(".playerRow").click(function(){
        $(".actionButton").removeAttr("disabled")
        $(".playerRow").removeClass("activeRow")
        $(this).addClass("activeRow")
        selectedBanRow = this
        selectedBan = $(this).data("id")
        selectedBanRConID = $(this).data("rconid")
        selectedBanSteamID = $(this).data("steamid")
        if(selectedBanSteamID == ""){
            $("#redirectToPlayer").attr("disabled", "")
        }
    })
</script>

<?php
    include("../scripts.php");

	use \Nizarii\ARC;

	try{
		$settings = get_config();
        $server = servers::get_by_id($_GET["id"]);
		$rcon = new ARC($server->rcon_ip, decrypt_text($server->rcon_password, $settings["key"]), intval($server->rcon_port));
        $players = $rcon->getPlayersArray();
        $rcon->disconnect();

        foreach($players as $value){
            echo "
                <tr data-id='{$value["0"]}' class='playerRow'>
                    <td>".filterXSS($value["4"])."</td>
                    <td>{$value["1"]}</td>
                    <td>{$value["3"]}</td>
                    <td>{$value["2"]}</td>
                </tr>
            ";
        }
        if(count($players) < 1){
            echo "<center><h4>The server is empty.</h4></center>";
        }
	}catch(Exception $e){
		echo $e->getMessage();
	}
?>

<script>
    $(".playerRow").click(function(){
        $(".actionButton").removeAttr("disabled")
        $(".playerRow").removeClass("activeRow")
        $(this).addClass("activeRow")
        selectedPlayer = $(this).data("id")
    })
</script>

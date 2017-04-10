<?php
    include("../../fillin/scripts.php");

    if(isset($_POST["mysqlIP"]) && isset($_POST["mysqlUsername"]) && isset($_POST["mysqlPassword"]) && isset($_POST["mysqlDBName"])){
        if(permissions::user_has_permission("settingspage")){
            $settings = get_config();
			$key = $settings["key"];
			$settings["mysql"]["ip"] = encrypt_text($_POST["mysqlIP"], $key);
			$settings["mysql"]["username"] = encrypt_text($_POST["mysqlUsername"], $key);
			$settings["mysql"]["password"] = encrypt_text($_POST["mysqlPassword"], $key);
			$settings["mysql"]["dbname"] = encrypt_text($_POST["mysqlDBName"], $key);
			file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/config.php", "<?php return " . var_export($settings, true) . ";");
			logs::add_log("panel configuration", "$1 edited the panel mysql settings", 100);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

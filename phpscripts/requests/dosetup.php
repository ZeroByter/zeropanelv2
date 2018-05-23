<?php
    include("../fillin/scripts.php");

    function rand_sha1($length=32){
        $max = ceil(32 / 40);
        $random = '';
        for ($i = 0; $i < $max; $i++) {
            $random .= sha1(microtime(true) . mt_rand(10000, 90000));
        }
        return substr($random, 0, $length);
    }

	function generateConfigFile(){
		$config = array();
		$config["linksOffset"] = "";
		$config["resourceLinksOffset"] = "";
		$config["cookie_id"] = rand_sha1(6);
        $last = str_replace(strrchr($_SERVER['REQUEST_URI'], '/'), '', $_SERVER['REQUEST_URI']).'/';
		$config["key"] = rand_sha1();
		$config["communityName"] = $_POST["communityName"];
		$config["mysql"] = array();
		$config["mysql"]["ip"] = encrypt_text($_POST["sqlHost"], $config["key"]);
        $config["mysql"]["port"] = encrypt_text($_POST["sqlPort"], $config["key"]);
		$config["mysql"]["username"] = encrypt_text($_POST["sqlUsername"], $config["key"]);
		$config["mysql"]["password"] = encrypt_text($_POST["sqlPassword"], $config["key"]);
		$config["mysql"]["dbname"] = encrypt_text($_POST["sqlDBName"], $config["key"]);
		$config["permissionsUpdateInterval"] = 8;
		$config["maxlevels"] = [];
        $config["maxlevels"]["coplevel"] = 7;
        $config["maxlevels"]["mediclevel"] = 5;
        $config["maxlevels"]["donatorlevel"] = 1;
        $config["licenseNames"] = [];
        $config["aliases"] = ["playerID" => "playerid", "lastPlayedAlias" => "timeupdated", "timeJoinedAlias" => "timejoined"];
        $config["banCount"] = 0;
        $config["enablePlayersBrowser"] = false;
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/config.php", "<?php return " . var_export($config, true) . ";");
	}

    if(!file_exists($_SERVER['DOCUMENT_ROOT'] . "/config.php")){
        if(@mysqli_ping(@mysqli_connect($_POST["sqlHost"], $_POST["sqlUsername"], $_POST["sqlPassword"], $_POST["sqlDBName"], $_POST["sqlPort"]))){
    		generateConfigFile();
            initliazeFirstTimeDatabase();
            if($_POST["useRCON"] == "Yes"){
                $_POST["useRCON"] = true;
            }else{
                $_POST["useRCON"] = false;
            }
            servers::create($_POST["serverName"], $_POST["useRCON"], $_POST["rconIP"], $_POST["rconPort"], $_POST["rconPassword"]);
    		permissions::create("Moderator", [], 1);
    		permissions::create("Admin", [], 2);
    		permissions::create("Super Admin", ["*"], 3);
    		accounts::create($_POST["defaultUsername"], $_POST["defaultPassword"], 3, $_POST["defaultPlayerID"]);
    		accounts::login($_POST["defaultUsername"], hash("sha256", $_POST["defaultPassword"]), false);
            echo "success";
        }else{
            echo "Connection to MySQL database failed! Are you using the correct details?";
        }
    }else{
        echo "Config file already exists!";
    }
?>

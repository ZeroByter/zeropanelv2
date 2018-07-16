<?php
    include("../fillin/scripts.php");

	function startsWith($haystack, $needle){
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	if(!empty($_POST["fixcode"]) && !empty($_POST["newip"]) && !empty($_POST["newport"]) && !empty($_POST["newusername"]) && !empty($_POST["newpassword"]) && !empty($_POST["newdbname"])){
		$mysqlFixCode = "";
		foreach(scandir(__DIR__ . "/../../") as $value){
			if(startsWith($value, "MySQL Error Fix Code - ")){
				$mysqlFixCode = str_replace(".txt", "", explode("MySQL Error Fix Code - ", $value)[1]);
			}
		}

		if(!empty($mysqlFixCode) && $_POST["fixcode"] == $mysqlFixCode){
			if(@mysqli_ping(@mysqli_connect($_POST["newip"], $_POST["newusername"], $_POST["newpassword"], $_POST["newdbname"], $_POST["newport"]))){
				$config = get_config();
				$config["mysql"]["ip"] = encrypt_text($_POST["newip"], $config["key"]);
                $config["mysql"]["port"] = encrypt_text($_POST["newport"], $config["key"]);
				$config["mysql"]["username"] = encrypt_text($_POST["newusername"], $config["key"]);
				$config["mysql"]["password"] = encrypt_text($_POST["newpassword"], $config["key"]);
				$config["mysql"]["dbname"] = encrypt_text($_POST["newdbname"], $config["key"]);
				write_config($config);

				foreach(scandir(__DIR__ . "/../../") as $value){
					if(startsWith($value, "MySQL Error Fix Code - ")){
						unlink(__DIR__ . "/../../" . $value);
					}
				}

				echo "success";
			}else{
				echo "Connection attempt with new details failed! Try a different MySQL login!";
			}
		}else{
			echo "Wrong MySQL Fix Code!";
		}
	}else{
		echo "Missing inputs!";
	}
?>

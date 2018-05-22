<?php
    include("../../fillin/scripts.php");

    if(isset($_POST["communityName"]) && isset($_POST["permUpdateInt"]) && isset($_POST["playerIDAlias"]) && isset($_POST["maxCopLevel"]) && isset($_POST["maxMedLevel"]) && isset($_POST["maxDonorLevel"]) && isset($_POST["linksOffset"]) && isset($_POST["resourceLinksOffset"]) && isset($_POST["enablePlayersBrowser"])){
        if(permissions::user_has_permission("settingspage")){
            $settings = get_config();
			$key = $settings["key"];
			$settings["linksOffset"] = $_POST["linksOffset"];
			$settings["resourceLinksOffset"] = $_POST["resourceLinksOffset"];
			$settings["communityName"] = $_POST["communityName"];
			$settings["permissionsUpdateInterval"] = $_POST["permUpdateInt"];
            $settings["aliases"]["playerID"] = $_POST["playerIDAlias"];
			$settings["maxlevels"]["coplevel"] = $_POST["maxCopLevel"];
			$settings["maxlevels"]["medlevel"] = $_POST["maxMedLevel"];
			$settings["maxlevels"]["donorlevel"] = $_POST["maxDonorLevel"];
			$settings["enablePlayersBrowser"] = $_POST["enablePlayersBrowser"] === "true" ? true : false;
            write_config($settings);
			logs::add_log("panel configuration", "$1 edited the panel main settings", 100);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

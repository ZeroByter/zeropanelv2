<?php
    include("../fillin/scripts.php");

    if(!empty($_POST["gameName"]) && !empty($_POST["niceName"])){
        if(permissions::user_has_permission("licensespage")){
            $config = get_config();
            $config["licenseNames"][] = [$_POST["gameName"], $_POST["niceName"]];
            write_config($config);
            logs::add_log("licenses", "$1 added license definition [gameName/{$_POST["gameName"]}] [niceName/{$_POST["niceName"]}]", 1);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

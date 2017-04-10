<?php
    include("../fillin/scripts.php");

    if(isset($_POST["id"]) && !empty($_POST["gameName"]) && !empty($_POST["niceName"])){
        if(permissions::user_has_permission("licensespage")){
            $config = get_config();
            $config["licenseNames"][$_POST["id"]] = [$_POST["gameName"], $_POST["niceName"]];
            write_config($config);
            logs::add_log("licenses", "$1 edited license definition [gameName/{$_POST["gameName"]}]", 1);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

<?php
    include("../fillin/scripts.php");

    if(isset($_POST["id"])){
        if(permissions::user_has_permission("licensespage")){
            $config = get_config();
            $licenseName = $config["licenseNames"][$_POST["id"]][0];
            unset($config["licenseNames"][$_POST["id"]]);
            write_config($config);
            logs::add_log("licenses", "$1 deleted license definition [license/$licenseName]", 2);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

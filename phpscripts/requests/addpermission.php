<?php
    include("../fillin/scripts.php");

    if(isset($_POST["name"]) && isset($_POST["accesslevel"])){
        if(!permissions::user_has_permission("ignoreaccesslevel") && $_POST["accesslevel"] >= accounts::get_current_account()->accesslevel){
            echo "You don't have permission to create a new permission with that access level!";
            return;
        }

        foreach(permissions::get_all() as $value){
            if($value->accesslevel == $_POST["accesslevel"]){
                echo "Access level is already taken!";
                return;
            }
        }

		logs::add_log("permissions", "$1 created access level [{$_POST["name"]}]", 15);
        $new = permissions::create($_POST["name"], [], $_POST["accesslevel"]);
        echo "success/$new";
    }else{
        echo "Missing inputs!";
    }
?>

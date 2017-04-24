<?php
    include("../fillin/scripts.php");

    if(isset($_POST["username"]) && isset($_POST["accesslevel"]) && isset($_POST["steamid"]) && isset($_POST["password"])){
        if(permissions::user_has_permission("addstaff")){
            if(empty(accounts::get_by_username($_POST["username"])->id)){
                if(!permissions::user_has_permission("ignoreaccesslevel") && $_POST["accesslevel"] >= accounts::get_current_account()->accesslevel){
                    echo "You don't have permission to create an account with that access level!";
                    return;
                }

                logs::add_log("staff", "$1 created staff user [{$_POST["username"]}] with [accesslevel/{$_POST["accesslevel"]}]", 4);
                $newaccount = accounts::create($_POST["username"], $_POST["password"], $_POST["accesslevel"], $_POST["steamid"]);
                echo "success/$newaccount";
            }else{
                echo "Account username already exists!";
            }
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

<?php
    include("../fillin/scripts.php");

    if(isset($_POST["id"]) && isset($_POST["pass"])){
        if(permissions::user_has_permission("editstaffpass")){
			$account = accounts::get_by_id($_POST["id"]);

            accounts::changePassword($_POST["id"], $_POST["pass"]);
			logs::add_log("staff", "$1 edited a new password for [staff/$account->username]", 4);

            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

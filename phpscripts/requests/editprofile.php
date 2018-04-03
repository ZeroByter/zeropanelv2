<?php
    include("../fillin/scripts.php");

    if(isset($_POST["currpass"]) && isset($_POST["newpass"])){
        $currAccount = accounts::get_current_account();
        if($_POST["newpass"] != ""){
            if(hash("sha256", hash("sha256", $_POST["currpass"]) . ":$currAccount->salt") == $currAccount->password){
                accounts::changePassword($currAccount->id, $_POST["newpass"]);
                echo "success";
            }else{
                echo "Your current password does not match!";
            }
        }else{
            echo "success";
        }
    }else{
        echo "Missing inputs!";
    }
?>

<?php
    include("../fillin/scripts.php");

    if(!empty($_POST["id"])){
		logs::add_log("staff", "$1 deleted [session/{$_POST["id"]}]", 80);
        sessions::delete_session_by_id($_POST["id"]);
        echo "success";
    }else{
        echo "Missing inputs!";
    }
?>

<?php
    include("../fillin/scripts.php");

    if(isset($_POST["id"]) && isset($_POST["username"]) && isset($_POST["accesslevel"]) && isset($_POST["steamid"])){
		$account = accounts::get_by_id($_POST["id"]);
        accounts::changeUsername($_POST["id"], $_POST["username"]);
        accounts::changePlayerID($_POST["id"], $_POST["steamid"]);
        accounts::changeAccessLevel($_POST["id"], $_POST["accesslevel"]);
		logs::add_log("staff", "$1 editted staff member [$account->username]", 25);
        echo "success";
    }else{
        echo "Missing inputs!";
    }
?>

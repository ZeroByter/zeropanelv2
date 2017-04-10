<?php
    include("../fillin/scripts.php");

    if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["rememberme"])){
        $_POST["rememberme"] = ($_POST["rememberme"] == "true") ? true : false;
        $login = accounts::login($_POST["username"], $_POST["password"], $_POST["rememberme"]);
        echo $login;
    }else{
        echo "error:Missing inputs!";
    }
?>

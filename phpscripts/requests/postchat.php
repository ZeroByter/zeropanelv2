<?php
    include("../fillin/scripts.php");

    if(isset($_POST["message"])){
        if($_POST["message"] == "" || $_POST["message"] == " "){
            echo "Message can't be empty!";
            return;
        }

        if(time() - $_SESSION["lastChatType"] > 6){
            $_SESSION["lastChatType"] = time();
            if(permissions::user_has_permission("typestaffchat")){
                staffchat::create($_POST["message"]);
    			logs::add_log("staff chat", "$1 typed new chat message [{$_POST["message"]}]", 4);

                echo "success";
            }else{
                echo "No permission!";
            }
        }else{
            echo "Spam cooldown! Please wait " . ($_SESSION["lastChatType"] + 6 - time()) . " more second(s)";
        }
    }else{
        echo "Missing inputs!";
    }
?>

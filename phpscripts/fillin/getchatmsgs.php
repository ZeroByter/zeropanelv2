<?php
    include("scripts.php");

    if(time() - $_SESSION["lastChatView"] >= 3){
        $_SESSION["lastChatView"] = time();
        if(staffchat::does_exist()){
            foreach(staffchat::get_all() as $value){
                $sender = accounts::get_by_id($value->sender);
                $sender->username = filterXSS($sender->username);
                $value->message = filterXSS($value->message);

                echo "<p data-toggle='tooltip' title='$value->time' data-placement='left'><b>$sender->username</b>: $value->message</p>";
            }
        }else{
            staffchat::create_db();
        }
    }
?>

<script>
    $("[data-toggle='tooltip']").tooltip()
</script>

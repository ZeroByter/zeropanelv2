<?php
    include("scripts.php");
	
	$linksOffset = "";
	$config = get_config();
	if($config["linksOffset"]){
		$linksOffset = $config["linksOffset"];
	}

    if(time() - $_SESSION["lastChatView"] >= 3){
        $_SESSION["lastChatView"] = time();
        if(staffchat::does_exist()){
            foreach(staffchat::get_all() as $value){
                $sender = accounts::get_by_id($value->sender);
                $sender->username = filterXSS($sender->username);
                $value->message = filterXSS($value->message);
				
				if(permissions::user_has_permission("editstaff")){
				$sender->username = "<a href='/{$linksOffset}staff/$sender->id'>$sender->username</a>";
				}
				
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

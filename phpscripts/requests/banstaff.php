<?php
    include("../fillin/scripts.php");

    if(isset($_POST["id"])){
        if(permissions::user_has_permission("banstaff")){
			$account = accounts::get_by_id($_POST["id"]);
			if($account->banned){
				logs::add_log("staff", "$1 unbanned [staff/$account->username]", 3);
			}else{
				logs::add_log("staff", "$1 banned [staff/$account->username]", 4);
			}
            accounts::toggleBan($_POST["id"]);
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>

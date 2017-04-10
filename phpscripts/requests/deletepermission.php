<?php
	include("/../fillin/scripts.php");

	if(isset($_POST["id"])){
        if(permissions::user_has_permission("deletepermissions")){
			if(count(permissions::get_all()) <= 1){
				echo "Can't delete the only single left access level!";
				return;
			}

			$accesslevel = permissions::get_by_id($_POST["id"]);
			permissions::delete($_POST["id"]);
			logs::add_log("permissions", "$1 deleted [accesslevel/$accesslevel->name]", 20);
			echo "success";
		}else{
			echo "No permission!";
		}
	}
?>

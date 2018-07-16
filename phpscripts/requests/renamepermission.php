<?php
	include("../fillin/scripts.php");

	if(!empty($_POST["id"]) && !empty($_POST["newName"])){
        if(permissions::user_has_permission("editpermissions")){
			$oldName = permissions::get_by_id($_POST["id"])->name;
			permissions::renamePermission($_POST["id"], $_POST["newName"]);
			logs::add_log("permissions", "$1 renamed [accesslevel/$oldName] to [{$_POST["newName"]}]", 8);
			echo "success";
		}else{
			echo "No permission!";
		}
	}
?>

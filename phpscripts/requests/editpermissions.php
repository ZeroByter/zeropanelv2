<?php
	include("../fillin/scripts.php");

	if(isset($_POST["id"]) && isset($_POST["name"]) && isset($_POST["type"])){
		$_POST["type"] = ($_POST["type"] === 'true');
        if(permissions::user_has_permission("editpermissions")){
			$accesslevel = permissions::get_by_id($_POST["id"]);
			$allPermissions = permissions::getAllPermissions();
			$currPermissions = json_decode($accesslevel->permissions);

			if(count($currPermissions) <= 1 && @$currPermissions[0] == "*"){
				$currPermissions = $allPermissions;
			}

			if($_POST["type"] == true){
				if(($key = array_search($_POST["name"], $currPermissions)) !== false) {
					unset($currPermissions[$key]);
				}
				logs::add_log("permissions", "$1 removed [permission/{$_POST["name"]}] from [accesslevel/$accesslevel->name]", 10);
			}else{
				array_push($currPermissions, $_POST["name"]);

				if(count($currPermissions) >= count($allPermissions)){
					$currPermissions = ["*"];
				}
				logs::add_log("permissions", "$1 added [permission/{$_POST["name"]}] to [accesslevel/$accesslevel->name]", 10);
			}
			permissions::edit($_POST["id"], json_encode(array_values($currPermissions)));
			echo "success";
		}else{
			echo "No permission!";
		}
	}
?>

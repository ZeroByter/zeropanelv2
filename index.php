<?php
	include("phpscripts/fillin/scripts.php");

	//Uncomment this to hide page errors.
	//Comment this to show errors
	//error_reporting(0);

	@$conn = get_mysql_conn();

	// Begin code to check MySQL connection error fixing code!
	if(gettype($conn) !== "object" && file_exists("config.php")){
		function rand_sha1($length=10){
			$max = ceil(32 / 40);
			$random = '';
			for ($i = 0; $i < $max; $i++) {
				$random .= sha1(microtime(true) . mt_rand(10000, 90000));
			}
			return substr($random, 0, $length);
		}

		function startsWith($haystack, $needle){
			$length = strlen($needle);
			return (substr($haystack, 0, $length) === $needle);
		}

		$createFileCode = true;
		foreach(scandir(__DIR__ . "\\") as $value){
			if(startsWith($value, "MySQL Error Fix Code - ")){
				$createFileCode = false;
			}
		}
		if($createFileCode){
			file_put_contents("MySQL Error Fix Code - ".rand_sha1().".txt", "");
		}
	}
	// End code to check MySQL connection error fixing code!

	//Main performance cause when loading pages is due to lots of user_has_permission functions. Adding onto the slower times is because user_has_permission checks if users are banned (which requires a MySQL query which takes EVEN longer).

	//Begin main page routing
	if(isset($_GET["page"])){
        $pageNum = clean($_GET["page"], 'int');
        if($pageNum < 1){
            $pageNum = 1;
        }
    }else{
        $pageNum = 1;
    }
	$showNavbar = true;
	$includeHead = true;
	$linksOffset = "";
	$resourceLinksOffset = "";
	if(file_exists("config.php")){
		$settings = include("config.php");
		$linksOffset = $settings["linksOffset"];
		$resourceLinksOffset = $settings["resourceLinksOffset"];

		$url = (parse_url($_SERVER['REQUEST_URI']));
	    $url['path'] = str_replace('.php', '', $url['path']);
	    $url['path'] = explode('/', $url['path']);

		$pageBase = count(explode("/", $linksOffset));
		$url['path'][$pageBase] = strtolower($url['path'][$pageBase]);
		if(count($url['path']) > $pageBase + 1 && $url['path'][$pageBase + 1] <> ''){
	        $url['path'][$pageBase + 1] = str_replace("%20", " ", $url['path'][$pageBase + 1]);
	    }
		for ($i = 0; $i < $pageBase; $i++) {
			array_shift($url["path"]);
		}
		$url["path"] = array_map("strtolower", $url["path"]);
		$furl = $url["path"];

		$page = "pages/errors/notfound.php"; //if we didn't assign a page dir by the end of the code, display 404
		if(accounts::is_logged_in()){
			$currAccount = accounts::get_current_account();
			if($currAccount->banned){
				$showNavbar = false;
				$page = "pages/banned.php";
			}else{
				$currAccessLevel = permissions::get_by_accesslevel($currAccount->accesslevel);
				if(!isset($_SESSION["permissions"])){
					$_SESSION["permissions"] = [];
				}
				if(isset($_SESSION["permissionsUpdateInterval"])){
					if(time() - $_SESSION["permissionsUpdateInterval"] > $settings["permissionsUpdateInterval"] || $_SESSION["permissions"] == []){ //update session usertags and permissions every xx seconds
						$_SESSION["permissionsUpdateInterval"] = time();
						$_SESSION["permissions"] = json_decode($currAccessLevel->permissions);
						//echo "updated usertags and permissions!";
					}
				}

				if($furl[0] == "" || $furl[0] == "index"){
					$page = "pages/dashboard.php";
				}
				if($furl[0] == "players"){
					$page = "pages/players.php";
					if(isset($furl[1]) && $furl[1] != ""){
						$player = players::get_by_steamid($furl[1]);
						if(isset($player->uid)){
							$bans = bans::get_by_steamid($player->playerid);
							$mainActive = "";
						    $levelsActive = "";
						    $moneyActive = "";
						    $licensesActive = "";
						    $inventoriesActive = "";
						    $vehiclesActive = "";
						    $housesActive = "";
						    $notesActive = "";
						    $bansActive = "";
							if(!isset($furl[2]) || $furl[2] == "" || $furl[2] == "main"){
								$mainActive = "active";
								$page = "pages/editplayer/dashboard.php";
							}
							if(isset($furl[2])){
								if($furl[2] == "levels"){
									if(permissions::user_has_permission("editlevels")){
										$levelsActive = "active";
										$page = "pages/editplayer/levels.php";
									}else{
										$page = "pages/errors/nopermission.php";
									}
								}
								if($furl[2] == "money"){
									if(permissions::user_has_permission("editmoneybank") || permissions::user_has_permission("editmoneycash")){
										$moneyActive = "active";
										$page = "pages/editplayer/money.php";
									}else{
										$page = "pages/errors/nopermission.php";
									}
								}
								if($furl[2] == "licenses"){
									if(permissions::user_has_permission("editlicenses")){
										$licensesActive = "active";
										$page = "pages/editplayer/licenses.php";
									}else{
										$page = "pages/errors/nopermission.php";
									}
								}
								if($furl[2] == "inventory"){
									if(permissions::user_has_permission("editinventory")){
										$inventoriesActive = "active";
										$page = "pages/editplayer/inventory.php";
									}else{
										$page = "pages/errors/nopermission.php";
									}
								}
								if($furl[2] == "vehicles"){
									$vehiclesActive = "active";
									$page = "pages/editplayer/vehicles.php";
								}
								if($furl[2] == "houses"){
									$housesActive = "active";
									$page = "pages/editplayer/houses.php";
								}
								if($furl[2] == "notes"){
									$notesActive = "active";
									$page = "pages/editplayer/notes.php";
								}
								if($furl[2] == "ban"){
									if(permissions::user_has_permission("banplayer")){
										$bansActive = "active";
										$page = "pages/editplayer/ban.php";
									}else{
										$page = "pages/errors/nopermission.php";
									}
								}
							}
						}
					}
				}

				if($furl[0] == "vehicles"){
					$page = "pages/vehicles.php";
					if(isset($furl[1]) && $furl[1] != ""){
						$vehicle = vehicles::get_by_id($furl[1]);
						$owner = players::get_by_steamid($vehicle->pid);
						if(isset($vehicle->id) && isset($owner->uid)){
							if(permissions::user_has_permission("editvehicles")){
								$page = "pages/editvehicle.php";
							}else{
								$page = "pages/errors/nopermission.php";
							}
						}
					}
				}

				if($furl[0] == "houses"){
					$page = "pages/houses.php";
					if(isset($furl[1]) && $furl[1] != ""){
						$house = houses::get_by_id($furl[1]);
						$owner = players::get_by_steamid($house->pid);
						if(isset($house->id) && isset($owner->uid)){
							if(permissions::user_has_permission("edithouses")){
								$page = "pages/edithouse.php";
							}else{
								$page = "pages/errors/nopermission.php";
							}
						}
					}
				}

				if($furl[0] == "rcon" && isset($furl[1]) && $furl[1] != ""){
					if(permissions::user_has_permission("rconpage")){
						$server = servers::get_by_id($furl[1]);
						if(isset($server->id) && $server->use_rcon){
							$page = "pages/rcon/index.php";
							if(isset($furl[2])){
								if($furl[2] == "bans"){
									$page = "pages/rcon/bans.php";
								}
								if($furl[2] == "players"){
									$page = "pages/rcon/players.php";
								}
							}
						}
					}else{
						$page = "pages/errors/nopermission.php";
					}
				}

				if($furl[0] == "servers"){
					if(permissions::user_has_permission("serverspage")){
						$page = "pages/servers.php";
						if(isset($furl[1]) && $furl[1] != ""){
							$server = servers::get_by_id($furl[1]);
							if(isset($server->id)){
								if(permissions::user_has_permission("editservers")){
									$page = "pages/editserver.php";
								}else{
									$page = "pages/errors/nopermission.php";
								}
							}
							if($furl[1] == "createserver"){
								if(permissions::user_has_permission("createservers")){
									$page = "pages/createserver.php";
								}else{
									$page = "pages/errors/nopermission.php";
								}
							}
						}
					}else{
						$page = "pages/errors/nopermission.php";
					}
				}

				if($furl[0] == "staff"){
					if(permissions::user_has_permission("staffpage")){
						$page = "pages/staff.php";
						if(isset($furl[1]) && $furl[1] != ""){
							$staff = accounts::get_by_id($furl[1]);
							if(isset($staff->id)){
								if(permissions::user_has_permission("editstaff")){
									$page = "pages/editstaff.php";
								}else{
									$page = "pages/errors/nopermission.php";
								}
							}
							if($furl[1] == "addnew"){
								if(permissions::user_has_permission("addstaff")){
									$page = "pages/addstaff.php";
								}else{
									$page = "pages/errors/nopermission.php";
								}
							}
							if($furl[1] == "sessions"){
								if(permissions::user_has_permission("deletesessions")){
									$page = "pages/deletesessions.php";
								}else{
									$page = "pages/errors/nopermission.php";
								}
							}
						}
					}else{
						$page = "pages/errors/nopermission.php";
					}
				}

				if($furl[0] == "profile"){
					$page = "pages/profile.php";
				}

				if($furl[0] == "settings"){
					if(permissions::user_has_permission("settingspage")){
						$page = "pages/settings.php";
					}else{
						$page = "pages/errors/nopermission.php";
					}

					if(isset($furl[1]) && $furl[1] == "licenses"){
						if(permissions::user_has_permission("licensespage")){
							$page = "pages/licenses.php";
						}else{
							$page = "pages/errors/nopermission.php";
						}
					}
				}

				if($furl[0] == "permissions"){
					if(permissions::user_has_permission("permissionspage")){
						$page = "pages/permissions.php";
						if(isset($furl[1]) && $furl[1] != ""){
								$permissionObj = permissions::get_by_id($furl[1]);
								if(isset($permissionObj->id)){
									if(permissions::user_has_permission("editpermissions")){
										$page = "pages/editpermission.php";
									}
								}
							if($furl[1] == "addnew"){
								if(permissions::user_has_permission("createpermissions")){
									$page = "pages/createpermissions.php";
								}else{
									$page = "pages/errors/nopermission.php";
								}
							}
						}
					}else{
						$page = "pages/errors/nopermission.php";
					}
				}

				if($furl[0] == "logs"){
					if(permissions::user_has_permission("logspage")){
						$page = "pages/logs.php";
					}else{
						$page = "pages/errors/nopermission.php";
					}
				}
				if($furl[0] == "moneylogs"){
					if(permissions::user_has_permission("logspage")){
						$page = "pages/moneylogs.php";
					}else{
						$page = "pages/errors/nopermission.php";
					}
				}

				if($furl[0] == "about"){
					$page = "pages/about.php";
				}
			}
		}else{
			$showNavbar = false;
			$page = "pages/login.php";
		}

		if($furl[0] == "playersbrowser" && @$settings["enablePlayersBrowser"]){
			$showNavbar = false;
			$page = "pages/playersbrowser.php";
		}

		if($furl[0] == "404"){
			$page = "pages/errors/notfound.php";
		}
		if($furl[0] == "403"){
			$page = "pages/errors/nopermission.php";
		}

		if(gettype($conn) !== "object" && file_exists("config.php")){ //MySQL error fix page
			$showNavbar = false;
			$page = "pages/mysqlerror.php";
		}
	}else{
		$showNavbar = false;
		$page = "pages/firstTime.php";
	}

	if($includeHead){
		include("phpscripts/fillin/head.php");
	}
	if($showNavbar){
		include("phpscripts/fillin/navbar.php");
	}
?>
<div id="body_div" <?php if(!$showNavbar){ echo "style='width:100%;'"; } ?>>
	<?php
		if(file_exists($page)){
			include($page);
		}else{
			include("/pages/errors/fileerror.php");
		}
	?>
</div>

<?php
	if(session_status() == 2 && isset($_SESSION["pageMessage"]) && $_SESSION["pageMessage"] !== ""){
		$pageMessageType = "success";
		if(isset($_SESSION["pageMessageType"])){
			$pageMessageType = $_SESSION["pageMessageType"];
		}
?>
<script>
	$.notify({
		message: "<?php echo $_SESSION["pageMessage"] ?>",
	},{
		type: "<?php echo $pageMessageType ?>",
		z_index: 103001,
		placement: {
			from: "top",
			align: "left"
		},
	})
</script>
<?php }
	if(isset($_SESSION)){
		unset($_SESSION["pageMessage"]);
		unset($_SESSION["pageMessageType"]);
	}

	if(gettype($conn) == "object"){
		mysqli_close($conn);
	}
 ?>

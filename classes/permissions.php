<?php
    class permissions{
        public function create_db(){
            $conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS permissions(
                id int(11) NOT NULL auto_increment,
                name varchar(64) NOT NULL,
                accesslevel int(11) NOT NULL,
                permissions text NOT NULL,
            PRIMARY KEY(id), UNIQUE id (id))");
            mysqli_close($conn);
        }

        public function get_all(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM permissions ORDER BY accesslevel DESC");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function get_all_limited(){
			if(permissions::user_has_permission("ignoreaccesslevel") || !accounts::is_logged_in()){
				return self::get_all();
			}

			$currentAccessLevel = accounts::get_current_account()->accesslevel;

            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM permissions WHERE accesslevel <= '$currentAccessLevel' ORDER BY accesslevel DESC");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

		public function get_by_id($id){
			$conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM permissions WHERE id='$id'");
    		mysqli_close($conn);

    		return mysqli_fetch_object($result);
		}

		public function get_by_accesslevel($id){
			$conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
            $accesslevel = mysqli_fetch_object(mysqli_query($conn, "SELECT accesslevel FROM permissions WHERE id='$id'"))->accesslevel;
    		$result = mysqli_query($conn, "SELECT * FROM permissions WHERE accesslevel='$accesslevel'");
    		mysqli_close($conn);

    		return mysqli_fetch_object($result);
		}

		public function create($name, $permissions, $accesslevel){
			if(gettype($permissions) == "array"){
				$permissions = json_encode($permissions);
			}

			$conn = get_mysql_conn();
            $name = mysqli_real_escape_string($conn, $name);
            $accesslevel = mysqli_real_escape_string($conn, $accesslevel);
            mysqli_query($conn, "INSERT INTO permissions(name, accesslevel, permissions) VALUES ('$name', '$accesslevel', '$permissions')");
            $createdID = mysqli_insert_id($conn);
            mysqli_close($conn);
            return $createdID;
		}

        public function delete($id){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
            $firstPermission = end(self::get_all());
            $accesslevel = self::get_by_id($id)->accesslevel;
    		mysqli_query($conn, "DELETE FROM permissions WHERE id='$id'");
    		mysqli_query($conn, "UPDATE accounts SET accesslevel='$firstPermission->accesslevel' WHERE accesslevel='$accesslevel'");
    		mysqli_close($conn);
        }

        public function edit($id, $permissions){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		mysqli_query($conn, "UPDATE permissions SET permissions='$permissions' WHERE id='$id'");
    		mysqli_close($conn);
        }

		public function renamePermission($id, $newName){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$newName = mysqli_real_escape_string($conn, $newName);
    		mysqli_query($conn, "UPDATE permissions SET name='$newName' WHERE id='$id'");
    		mysqli_close($conn);
        }

		public function user_has_permission($permission){
			$permissions = [];
            if(accounts::get_current_account()->banned){
                return false;
            }
			if(isset($_SESSION["permissions"])){ $permissions = $_SESSION["permissions"]; }
			foreach($permissions as $value){
				$value = str_replace(" ", "", $value);
				if($value == "*"){
					return true;
				}elseif($value == $permission){
					return true;
				}
			}
        	return false;
        }

		public function accesslevel_has_permission($id, $permission){
			$permissions = json_decode(self::get_by_id($id)->permissions);
			foreach($permissions as $value){
				$value = str_replace(" ", "", $value);
				if($value == "*"){
					return true;
				}elseif($value == $permission){
					return true;
				}
			}
        	return false;
        }

		public function getpermissions(){
            $permissions = [];

			$permissions["navigationbar"] = ["Navigation Bar"];
			$permissions["navigationbar"][] = ["serverspage", "View the servers page"];
			$permissions["navigationbar"][] = ["rconpage", "View the RCon page"];
			$permissions["navigationbar"][] = ["staffpage", "View the staff page"];
			$permissions["navigationbar"][] = ["settingspage", "View the settings page"];
			$permissions["navigationbar"][] = ["licensespage", "View the licenses page"];
			$permissions["navigationbar"][] = ["permissionspage", "View the permissions page"];
			$permissions["navigationbar"][] = ["logspage", "View the logs page"];
            $permissions["navigationbar"][] = ["sessionspage", "View the sessions page"];

            $permissions["chat"] = ["Staff Chat"];
            $permissions["chat"][] = ["viewstaffchat", "View the staff chat"];
            $permissions["chat"][] = ["typestaffchat", "Type in the staff chat"];

            $permissions["playerpage"] = ["Edit Player Page"];
            $permissions["playerpage"][] = ["editlevels", "Edit in-game levels"];
            $permissions["playerpage"][] = ["editmoneybank", "Add or remove bank money"];
            $permissions["playerpage"][] = ["editmoneycash", "Add or remove cash money"];
            $permissions["playerpage"][] = ["editlicenses", "Edit licenses"];
            $permissions["playerpage"][] = ["editinventory", "Edit all inventories"];
            $permissions["playerpage"][] = ["releasefromjail", "Release the player from jail"];
            $permissions["playerpage"][] = ["addnotes", "Add player notes"];
            $permissions["playerpage"][] = ["banplayer", "Ban player"];

            $permissions["vehiclespage"] = ["Edit Vehicles Page"];
            $permissions["vehiclespage"][] = ["editvehicles", "Edit vehicles"];
            $permissions["vehiclespage"][] = ["editvehinventory", "Edit inventory"];
            $permissions["vehiclespage"][] = ["deletevehicle", "Delete vehicle"];

            $permissions["housespage"] = ["Edit Houses Page"];
            $permissions["housespage"][] = ["edithouses", "Edit houses"];
            $permissions["housespage"][] = ["edithousesinventory", "Edit inventory"];
            $permissions["housespage"][] = ["deletehouses", "Delete houses"];

            $permissions["rconpage"] = ["RCon Page"];
            $permissions["rconpage"][] = ["viewrconbans", "View bans"];
            $permissions["rconpage"][] = ["removerconbans", "Remove bans"];
            $permissions["rconpage"][] = ["kickrconplayers", "Kick players from the server"];
            $permissions["rconpage"][] = ["sayrconglobal", "Send server global message"];
            $permissions["rconpage"][] = ["sayrconprivate", "Say server private message"];

			$permissions["serverspage"] = ["Edit Servers Page"];
			$permissions["serverspage"][] = ["createservers", "Create/delete servers"];
			$permissions["serverspage"][] = ["editservers", "Edit servers"];

			$permissions["permissionspage"] = ["Edit Permissions Page"];
			$permissions["permissionspage"][] = ["editpermissions", "Edit permissions"];
			$permissions["permissionspage"][] = ["deletepermissions", "Delete permissions"];
			$permissions["permissionspage"][] = ["createpermissions", "Create permissions"];
			$permissions["permissionspage"][] = ["ignoreaccesslevel", "Ignore access level restrictions"];

            $permissions["staffpage"] = ["Staff Page"];
            $permissions["staffpage"][] = ["editstaff", "Edit staff"];
            $permissions["staffpage"][] = ["addstaff", "Add staff"];
            $permissions["staffpage"][] = ["banstaff", "Ban/unban staff"];
            $permissions["staffpage"][] = ["editstaffpass", "Edit staff's password"];

            $permissions["sessionspage"] = ["Sessions Page"];
            $permissions["sessionspage"][] = ["deletesessions", "Delete/logout sessions"];

            return $permissions;
		}

        public function getAllPermissions(){
            $array = [];

            foreach(self::getpermissions() as $key=>$value){
                foreach($value as $key2=>$permission){
                    if($key2 != 0){
                        $array[] = $permission[0];
                    }
                }
            }

            return $array;
        }
    }
?>

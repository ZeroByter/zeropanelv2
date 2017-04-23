<?php
    class bans{
		public function create_db(){
            $conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS bans(
                id int(10) NOT NULL auto_increment,
                name varchar(255) NOT NULL,
                steamid varchar(255) NOT NULL,
                guid varchar(255) NOT NULL,
                length varchar(255) NOT NULL,
                created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                reason text NOT NULL,
                evid text NOT NULL,
                notes longtext NOT NULL,
                active int(11) NOT NULL,
                staff varchar(255) NOT NULL,
                banid int(11) NOT NULL,
            PRIMARY KEY(id), UNIQUE id (id))");
            mysqli_close($conn);
        }
		
        public function get_all($page){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM bans ORDER BY created DESC");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function get_by_steamid($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM bans WHERE steamid='$id' ORDER BY created DESC");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function get_all_active($id = ""){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            if($id == ""){
                $result = mysqli_query($conn, "SELECT * FROM bans WHERE active = 1 AND (UNIX_TIMESTAMP(created) - UNIX_TIMESTAMP(NOW()) + length * 60 > 0 OR length = '0') ORDER BY created DESC");
            }else{
                $result = mysqli_query($conn, "SELECT * FROM bans WHERE steamid='$id' AND active = 1 AND (UNIX_TIMESTAMP(created) - UNIX_TIMESTAMP(NOW()) + length * 60 > 0 OR length = '0') ORDER BY created DESC");
            }
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function get_by_banid($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM bans WHERE banid='$id'");
            mysqli_close($conn);

            return mysqli_fetch_object($result);
        }

        public function getBanCount(){
            return get_config()["banCount"];
            //return intval(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/bancount.txt"));
        }

        public function AddBanCount(){
            $config = get_config();
            $config["banCount"] = $config["banCount"] + 1;
            write_config($config);
            //$number = self::getBanCount();
        	//file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/bancount.txt", $number + 1);
        }

        public function create($steamID, $guid, $length, $reason, $notes=""){
            $currAccount = accounts::get_current_account();
            $player = players::get_by_steamid($steamID);

            $conn = get_mysql_conn();
            $steamID = mysqli_real_escape_string($conn, $steamID);
            $guid = mysqli_real_escape_string($conn, $guid);
            $length = mysqli_real_escape_string($conn, $length);
            $reason = mysqli_real_escape_string($conn, $reason);
            $notes = mysqli_real_escape_string($conn, $notes);
            $result = mysqli_query($conn, "INSERT INTO bans(banid, name, steamid, guid, length, reason, notes, staff, created) VALUES ('".self::getBanCount()."', '$player->name', '$steamID', '$guid', '$length', '$reason', '$notes', '$currAccount->id', '".time()."')");
            mysqli_close($conn);
        }

        public function inactive($banid){
            $conn = get_mysql_conn();
            $banid = mysqli_real_escape_string($conn, $banid);
    		mysqli_query($conn, "UPDATE bans SET active='0' WHERE banid='$banid'");
            mysqli_close($conn);
        }
    }
?>

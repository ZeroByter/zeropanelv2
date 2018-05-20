<?php
    class bans{
		public function create_db(){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS bans(
                id int(10) NOT NULL auto_increment,
                name varchar(255) NOT NULL,
                steamid varchar(255) NOT NULL,
                guid varchar(255) NOT NULL,
                length varchar(255) NOT NULL,
                created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                reason text NOT NULL,
                evid text NOT NULL,
                notes longtext NOT NULL,
                active boolean NOT NULL DEFAULT 1,
                staff varchar(255) NOT NULL,
                banid int(11) NOT NULL,
            PRIMARY KEY(id), UNIQUE id (id))");
            $stmt->execute();
        }

        public function get_all($page){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM bans ORDER BY created DESC");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function get_by_steamid($id){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("SELECT * FROM bans WHERE steamid=? ORDER BY created DESC");
            $stmt->execute(array($id));

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function get_all_active($id = ""){
            $conn = get_mysql_conn();
            if($id == ""){
                $stmt = $conn->prepare("SELECT * FROM bans WHERE active = 1 AND (UNIX_TIMESTAMP(created) - UNIX_TIMESTAMP(NOW()) + length * 60 > 0 OR length = '0') ORDER BY created DESC");
                $stmt->execute();
            }else{
                $stmt = $conn->prepare("SELECT * FROM bans WHERE steamid=? AND active = 1 AND (UNIX_TIMESTAMP(created) - UNIX_TIMESTAMP(NOW()) + length * 60 > 0 OR length = '0') ORDER BY created DESC");
                $stmt->execute(array($id));
            }

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function get_by_banid($id){
            $conn = get_mysql_conn();

            $stmt = $conn->prepare("SELECT * FROM bans WHERE banid=?");
            $stmt->execute(array($id));

            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if(strtotime(@$result->created) - time() + @$result->length * 60 > 0 || @$result->length == 0){}else{
                self::inactive($id);
            }

            return $result;
        }

        public function getBanCount(){
            return get_config()["banCount"];
        }

        public function AddBanCount(){
            $config = get_config();
            $config["banCount"] = $config["banCount"] + 1;
            write_config($config);
        }

        public function create($steamID, $guid, $length, $reason, $notes=""){
            $currAccount = accounts::get_current_account();
            $player = players::get_by_steamid($steamID);

            $conn = get_mysql_conn();
            $stmt = $conn->prepare("INSERT INTO bans(banid, name, steamid, guid, length, reason, notes, staff, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute(array($self::getBanCount(), $player->name, $steamID, $guid, $length, $reason, $notes, $currAccount->id, 1));
        }

        public function active($banid){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("UPDATE bans SET active='1' WHERE banid=?");
            $stmt->execute(array($banid));
        }

        public function inactive($banid){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("UPDATE bans SET active='0' WHERE banid=?");
            $stmt->execute(array($banid));
        }
    }
?>

<?php
    class logs{
        public function create_db(){
            $conn = get_mysql_conn();

            $stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS logs(
                id int(11) NOT NULL auto_increment,
                owner int(11) NOT NULL,
                ip varchar(64) NOT NULL,
                time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                title varchar(128) NOT NULL,
                description text NOT NULL,
                level int(11) NOT NULL,
            PRIMARY KEY(id), UNIQUE id (id))");
            $stmt->execute(array());
        }

        public function get_by_id($id){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("SELECT * FROM logs WHERE id=?");
            $stmt->execute(array($id));

    		return $stmt->fetch(PDO::FETCH_OBJ);
        }

        public function get_all($page){
            $search = "";
            if(isset($_GET["search"])){
                $search = $_GET["search"];
            }

			$conn = get_mysql_conn();
            $stmt = $conn->prepare("SELECT * FROM logs WHERE `time` LIKE ? OR title LIKE ? ORDER BY time DESC LIMIT ". ($page-1) * 30 .", 30");
            $stmt->execute(array("%$search%", "%$search%"));
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
		}

        public function get_all_real(){
            $conn = get_mysql_conn();

            $stmt = $conn->prepare("SELECT id FROM logs");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

		public function get_all_by_owner($owner, $limit=12){
			$conn = get_mysql_conn();
            $stmt = $conn->prepare("SELECT * FROM logs WHERE owner=? ORDER BY time DESC LIMIT ?");
            $stmt->execute(array($owner, $limit));
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
		}

        public function add_log($title, $description, $level=1, $overrideOwner=null){
            $ip = $_SERVER["REMOTE_ADDR"];

            if(accounts::is_logged_in()){
                $currAccount = accounts::get_current_account();
                $owner = $currAccount->id;
                $username = $currAccount->username;
                $steamid = $currAccount->playerid;
            }else{
                $owner = 0;
                $username = $ip;
                $steamid = "N/A";
            }
			if(isset($overrideOwner)){
				$owner = $overrideOwner;
			}

            $conn = get_mysql_conn();
            $description = json_encode(preg_replace("/\\$1/", "$username ($steamid)", $description));
            $description = substr($description, 1, -1);
            $time = time();
            $stmt = $conn->prepare("INSERT INTO logs(owner, ip, title, description, level) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(array($owner, $ip, $title, $description, $level));
        }
    }
?>

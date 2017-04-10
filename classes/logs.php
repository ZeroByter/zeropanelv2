<?php
    class logs{
        public function create_db(){
            $conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS logs(
                id int(11) NOT NULL auto_increment,
                owner int(11) NOT NULL,
                ip varchar(64) NOT NULL,
                time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                title varchar(128) NOT NULL,
                description text NOT NULL,
                level int(11) NOT NULL,
            PRIMARY KEY(id), UNIQUE id (id))");
            mysqli_close($conn);
        }

        public function get_by_id($id){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM logs WHERE id='$id'");
    		mysqli_close($conn);

    		return mysqli_fetch_object($result);
        }

		public function get_all($page){
            $search = "";
            if(isset($_GET["search"])){
                $search = $_GET["search"];
            }

			$conn = get_mysql_conn();
            $result = mysqli_query($conn, "SELECT * FROM logs WHERE `time` LIKE '%$search%' OR title LIKE '%$search%' ORDER BY time DESC LIMIT ". ($page-1) * 30 .", 30");
            mysqli_close($conn);
            $array = array();

            while($array[] = mysqli_fetch_object($result));
            return array_filter($array);
		}

        public function get_all_real(){
            $search = "";
            if(isset($_GET["search"])){
                $search = $_GET["search"];
            }

			$conn = get_mysql_conn();
            $result = mysqli_query($conn, "SELECT id FROM logs WHERE `time` LIKE '%$search%' OR title LIKE '%$search%'");
            mysqli_close($conn);
            $array = array();

            while($array[] = mysqli_fetch_object($result));
            return array_filter($array);
        }

		public function get_all_by_owner($owner, $limit=12){
			$conn = get_mysql_conn();
            $result = mysqli_query($conn, "SELECT * FROM logs WHERE owner='$owner' ORDER BY time DESC LIMIT $limit");
            mysqli_close($conn);

            while($array[] = mysqli_fetch_object($result));
            return array_filter($array);
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
            $title = mysqli_real_escape_string($conn, $title);
            $description = mysqli_real_escape_string($conn, $description);
            $description = json_encode(preg_replace("/\\$1/", "$username ($steamid)", $description));
            $description = substr($description, 1, -1);
            $time = time();
            $result = mysqli_query($conn, "INSERT INTO logs(owner, ip, title, description, level) VALUES ('$owner', '$ip', '$title', '$description', '$level')");
            mysqli_close($conn);
        }
    }
?>

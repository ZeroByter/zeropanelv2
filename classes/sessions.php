<?php
    class sessions{
        public function create_db(){
            $conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS sessions(
                id int(8) NOT NULL auto_increment,
                ip varchar(32) NOT NULL,
                sessionid varchar(64) NOT NULL,
                accountid int(8) NOT NULL,
                created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                lastused TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                browser varchar(64) NOT NULL,
                browserVersion varchar(64) NOT NULL,
                platform varchar(64) NOT NULL,
            PRIMARY KEY(id), UNIQUE id (id))");
            mysqli_close($conn);
        }

        private function generate_salt(){
            return hash("sha256", bin2hex(openssl_random_pseudo_bytes(32)));
        }

		public function get_all(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM sessions");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function get_all_display($page){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM sessions LIMIT ". ($page-1) * 30 .", 30");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function get_by_id($id){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM sessions WHERE id='$id'");
    		mysqli_close($conn);

    		return mysqli_fetch_object($result);
        }

        public function get_session($sessionid){
            $conn = get_mysql_conn();
    		//$sessionid = mysqli_real_escape_string($conn, hash("sha256", $sessionid));
    		$sessionid = mysqli_real_escape_string($conn, $sessionid);
            $result = mysqli_query($conn, "SELECT * FROM sessions WHERE sessionid='$sessionid'");
    		mysqli_close($conn);
            $result = mysqli_fetch_object($result);

            if($result->ip != $_SERVER["REMOTE_ADDR"]){
                return false;
            }

    		return $result;
        }

        public function add_session($accountid){
            $conn = get_mysql_conn();
            $ip = $_SERVER["REMOTE_ADDR"];
            $sessionid = hash("sha256", self::generate_salt());
            $accountid = mysqli_real_escape_string($conn, $accountid);

            $browserClass = new Browser();
            $browser = $browserClass->getBrowser();
            $browserVersion = $browserClass->getVersion();
            $platform = $browserClass->getPlatform();

            mysqli_query($conn, "INSERT INTO sessions(ip, sessionid, accountid, browser, browserVersion, platform) VALUES ('$ip', '$sessionid', '$accountid', '$browser', '$browserVersion', '$platform')");
            $createdID = mysqli_insert_id($conn);
            mysqli_close($conn);
            return $createdID;
        }

        public function delete_session_by_id($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            mysqli_query($conn, "DELETE FROM sessions WHERE id='$id'");
            mysqli_close($conn);
        }

        public function delete_session($sessionid){
            $conn = get_mysql_conn();
            $sessionid = mysqli_real_escape_string($conn, $sessionid);
            mysqli_query($conn, "DELETE FROM sessions WHERE sessionid='$sessionid'");
            mysqli_close($conn);
        }

        public function update_lastused($sessionid){
            $conn = get_mysql_conn();
            $sessionid = mysqli_real_escape_string($conn, $sessionid);
            $time = time();
            mysqli_query($conn, "UPDATE sessions SET lastused='$time' WHERE sessionid='$sessionid'");
            mysqli_close($conn);
        }
    }
?>

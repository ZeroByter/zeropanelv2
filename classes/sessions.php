<?php
    class sessions{
        public function create_db(){
            $conn = get_mysql_conn();

            $stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS sessions(
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
            $stmt->execute();
        }

        private function generate_salt(){
            return hash("sha256", bin2hex(openssl_random_pseudo_bytes(32)));
        }

		public function get_all(){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("SELECT * FROM sessions");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function get_all_display($page){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("SELECT * FROM sessions LIMIT ". ($page-1) * 30 .", 30");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function get_by_id($id){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM sessions WHERE id=?");
            $stmt->execute(array($id));
            return $stmt->fetch(PDO::FETCH_OBJ);
        }

        public function get_session($sessionid){
            $conn = get_mysql_conn();

            $stmt = $conn->prepare("SELECT * FROM sessions WHERE sessionid=?");
            $stmt->execute(array($sessionid));
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if($result->ip != $_SERVER["REMOTE_ADDR"]){
                return false;
            }

    		return $result;
        }

        public function add_session($accountid){
            $conn = get_mysql_conn();
            $ip = $_SERVER["REMOTE_ADDR"];
            $sessionid = hash("sha256", self::generate_salt());

            $browserClass = new Browser();
            $browser = $browserClass->getBrowser();
            $browserVersion = $browserClass->getVersion();
            $platform = $browserClass->getPlatform();

            $stmt = $conn->prepare("INSERT INTO sessions(ip, sessionid, accountid, browser, browserVersion, platform) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute(array($ip, $sessionid, $accountid, $browser, $browserVersion, $platform));
            $createdID = $conn->lastInsertId();
            return $createdID;
        }

        public function delete_session_by_id($id){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("DELETE FROM sessions WHERE id=?");
            $stmt->execute(array($id));
        }

        public function delete_session($sessionid){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("DELETE FROM sessions WHERE sessionid=?");
            $stmt->execute(array($sessionid));
        }

        public function update_lastused($sessionid){
            $conn = get_mysql_conn();
            $time = time();
            $stmt = $conn->prepare("UPDATE sessions SET lastused=? WHERE sessionid=?");
            $stmt->execute(array($time, $sessionid));
        }
    }
?>

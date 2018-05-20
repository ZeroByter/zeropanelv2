<?php
    class servers{
        public function create_db(){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS servers(
                id int(11) NOT NULL auto_increment,
                name varchar(64) NOT NULL,
                use_rcon boolean NOT NULL,
                rcon_ip varchar(255) NOT NULL,
                rcon_port varchar(255) NOT NULL,
                rcon_password varchar(255) NOT NULL,
            PRIMARY KEY(id), UNIQUE id (id))");
            $stmt->execute();
        }

		public function get_all($page){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM servers WHERE id LIKE ? OR name LIKE ? LIMIT ". ($page-1) * 30 .", 30");
            $stmt->execute(array($search, "%$search%"));
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

		public function get_all_real(){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM servers WHERE id LIKE ? OR name LIKE ?");
            $stmt->execute(array($search, "%$search%"));
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function get_by_id($id){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM servers WHERE id=?");
            $stmt->execute(array($id));
            return $stmt->fetch(PDO::FETCH_OBJ);
        }

        public function create($name, $use_rcon, $rcon_ip, $rcon_port, $rcon_password){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("INSERT INTO servers(name, use_rcon, rcon_ip, rcon_port, rcon_password) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(array($name, $use_rcon, $rcon_ip, $rcon_port, $rcon_password));
            $createdID = $conn->lastInsertId();
            return $createdID;
        }

        public function delete($id){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("DELETE FROM servers WHERE id=?");
            $stmt->execute(array($id));
        }

        public function editName($id, $name){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("UPDATE servers SET name=? WHERE id=?");
            $stmt->execute(array($name, $id));
        }

        public function editRCon($id, $use_rcon, $rcon_ip, $rcon_port, $rcon_password){
            $conn = get_mysql_conn();
            if($rcon_ip == "localhost"){
                $rcon_ip = "127.0.0.1";
            }
            $settings = get_config();
			$rcon_password = encrypt_text($rcon_password, $settings["key"]);
            $stmt = $conn->prepare("UPDATE servers SET use_rcon=?,rcon_ip=?,rcon_port=?,rcon_password=? WHERE id=?");
            $stmt->execute(array($use_rcon, $rcon_ip, $rcon_port, $rcon_password, $id));
        }
    }
?>

<?php
    class servers{
        public function create_db(){
            $conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS servers(
                id int(11) NOT NULL auto_increment,
                name varchar(64) NOT NULL,
                use_rcon boolean NOT NULL,
                rcon_ip varchar(255) NOT NULL,
                rcon_port varchar(255) NOT NULL,
                rcon_password varchar(255) NOT NULL,
            PRIMARY KEY(id), UNIQUE id (id))");
            mysqli_close($conn);
        }

		public function get_all($page){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM servers WHERE id LIKE '$search' OR name LIKE '%$search%' LIMIT ". ($page-1) * 30 .", 30");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

		public function get_all_real(){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM servers WHERE id LIKE '$search' OR name LIKE '%$search%'");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function get_by_id($id){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM servers WHERE id='$id'");
    		mysqli_close($conn);

    		return mysqli_fetch_object($result);
        }

        public function create($name, $use_rcon, $rcon_ip, $rcon_port, $rcon_password){
            $conn = get_mysql_conn();
            $name = mysqli_real_escape_string($conn, $name);
            $use_rcon = mysqli_real_escape_string($conn, $use_rcon);
            $rcon_ip = mysqli_real_escape_string($conn, $rcon_ip);
            $rcon_port = mysqli_real_escape_string($conn, $rcon_port);
            $settings = get_config();
            $rcon_password = encrypt_text($rcon_password, $settings["key"]);
            mysqli_query($conn, "INSERT INTO servers(name, use_rcon, rcon_ip, rcon_port, rcon_password) VALUES ('$name', '$use_rcon', '$rcon_ip', '$rcon_port', '$rcon_password')");
            $createdID = mysqli_insert_id($conn);
            mysqli_close($conn);
            return $createdID;
        }

        public function delete($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            mysqli_query($conn, "DELETE FROM servers WHERE id='$id'");
            mysqli_close($conn);
        }

        public function editName($id, $name){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $name = mysqli_real_escape_string($conn, $name);
            mysqli_query($conn, "UPDATE servers SET name='$name' WHERE id='$id'");
            mysqli_close($conn);
        }

        public function editRCon($id, $use_rcon, $rcon_ip, $rcon_port, $rcon_password){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
            $use_rcon = mysqli_real_escape_string($conn, $use_rcon);
            if($rcon_ip == "localhost"){
                $rcon_ip = "127.0.0.1";
            }
            $rcon_ip = mysqli_real_escape_string($conn, $rcon_ip);
            $rcon_port = mysqli_real_escape_string($conn, $rcon_port);
            $settings = get_config();
			$rcon_password = encrypt_text($rcon_password, $settings["key"]);
            mysqli_query($conn, "UPDATE servers SET use_rcon='$use_rcon',rcon_ip='$rcon_ip',rcon_port='$rcon_port',rcon_password='$rcon_password' WHERE id='$id'");
            mysqli_close($conn);
        }
    }
?>

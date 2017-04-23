<?php
    class playernotes{
		public function create_db(){
            $conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS player_notes(
                id int(10) NOT NULL auto_increment,
                admin varchar(50) NOT NULL,
                adminuid int(11) NOT NULL,
                player varchar(50) NOT NULL,
                playeruid int(11) NOT NULL,
                type varchar(100) NOT NULL,
                notes varchar(500) NOT NULL,
                punish varchar(50) NOT NULL,
                time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id), UNIQUE id (id))");
            mysqli_close($conn);
        }
		
        public function get_all($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM player_notes WHERE id='$id' ORDER BY time DESC");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function create($playerid, $note){
            $currAccount = accounts::get_current_account();
            $player = players::get_by_id($playerid);

            $conn = get_mysql_conn();
            $playerid = mysqli_real_escape_string($conn, $playerid);
            $note = mysqli_real_escape_string($conn, $note);
            $result = mysqli_query($conn, "INSERT INTO player_notes(admin, adminuid, player, playeruid, notes) VALUES ('$currAccount->username', '$currAccount->id', '$player->name', '$player->uid', '$note')");
            mysqli_close($conn);
        }
    }
?>

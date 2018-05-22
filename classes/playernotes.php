<?php
    class playernotes{
		public function create_db(){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS player_notes(
                id int(10) NOT NULL auto_increment,
                admin varchar(50) NOT NULL,
                adminuid int(11) NOT NULL,
                player varchar(50) NOT NULL,
                playeruid int(11) NOT NULL,
                notes varchar(500) NOT NULL,
                time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id), UNIQUE id (id))");
            $stmt->execute();
        }

        public function get_all($id){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("SELECT * FROM player_notes WHERE id=? ORDER BY time DESC");
            $stmt->execute(array($id));
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function create($playerid, $note){
            $currAccount = accounts::get_current_account();
            $player = players::get_by_id($playerid);

            $conn = get_mysql_conn();
            $stmt = $conn->prepare("INSERT INTO player_notes(admin, adminuid, player, playeruid, notes) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(array($currAccount->username, $currAccount->id, $player->name, $player->uid, $note));
        }
    }
?>

<?php
    class moneylogs{
		public function create_db(){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS le_compensation_log(
                id int(10) NOT NULL auto_increment,
                admins_id int(35) NOT NULL,
                admins_playerid int(50) NOT NULL,
                admins_name int(50) NOT NULL,
                money_before int(50) NOT NULL,
                money_given int(50) NOT NULL,
                money_now int(50) NOT NULL,
                update_type varchar(50) NOT NULL,
                players_id int(50) NOT NULL,
                players_playerid int(50) NOT NULL,
                players_name int(50) NOT NULL,
                time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY(id), UNIQUE id (id))");
            $stmt->execute();
        }

        public function get_all($page){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM le_compensation_log WHERE admins_id LIKE ? OR admins_playerid LIKE ? OR admins_name LIKE ? OR players_id LIKE ? OR players_playerid LIKE ? OR players_name LIKE ? ORDER BY time DESC LIMIT ". ($page-1) * 30 .", 30");
            $stmt->execute(array($search, $search, "%$search%", $search, $search, "%$search%"));

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function get_all_real(){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM le_compensation_log WHERE admins_id LIKE ? OR admins_playerid LIKE ? OR admins_name LIKE ? OR players_id LIKE ? OR players_playerid LIKE ? OR players_name LIKE ? ORDER BY time DESC");
            $stmt->execute(array($search, $search, "%$search%", $search, $search, "%$search%"));

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function get_all_by_player($id){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("SELECT * FROM le_compensation_log WHERE players_id=? ORDER BY time DESC");
            $stmt->execute(array($id));

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function create($moneyBefore, $moneyGiven, $moneyNow, $playersID, $updateType){
            $currAccount = accounts::get_current_account();
            $player = players::get_by_id($playersID);

            $conn = get_mysql_conn();
            $stmt = $conn->prepare("INSERT INTO le_compensation_log(admins_id, admins_playerid, admins_name, money_before, money_given, money_now, update_type, players_id, players_playerid, players_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute(array($currAccount->id, $currAccount->playerid, $currAccount->username, $moneyBefore, $moneyGiven, $moneyNow, $updateType, $playersID, $player->playerid, $player->name));
        }

        public function getMoneyAdded(){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT SUM(money_given) AS sum FROM le_compensation_log WHERE money_given > 0");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ)->sum;
        }

        public function getMoneyTaken(){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT SUM(money_given) AS sum FROM le_compensation_log WHERE money_given < 0");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ)->sum;
        }
    }
?>

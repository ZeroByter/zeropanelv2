<?php
    class moneylogs{
		public function create_db(){
            $conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS le_compensation_log(
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
            mysqli_close($conn);
        }
		
        public function get_all($page){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM le_compensation_log WHERE admins_id LIKE '$search' OR admins_playerid LIKE '$search' OR admins_name LIKE '%$search%' OR players_id LIKE '$search' OR players_playerid LIKE '$search' OR players_name LIKE '%$search%' ORDER BY time DESC LIMIT ". ($page-1) * 30 .", 30");
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
    		$result = mysqli_query($conn, "SELECT * FROM le_compensation_log WHERE admins_id LIKE '$search' OR admins_playerid LIKE '$search' OR admins_name LIKE '$search' OR players_id LIKE '$search' OR players_playerid LIKE '$search' OR players_name LIKE '$search' ORDER BY time DESC");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function get_all_by_player($id){
            $conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM le_compensation_log WHERE players_id='$id' ORDER BY time DESC");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function create($moneyBefore, $moneyGiven, $moneyNow, $playersID, $updateType){
            $currAccount = accounts::get_current_account();
            $player = players::get_by_id($playersID);

            $conn = get_mysql_conn();
            $moneyBefore = mysqli_real_escape_string($conn, $moneyBefore);
            $moneyGiven = mysqli_real_escape_string($conn, $moneyGiven);
            $moneyNow = mysqli_real_escape_string($conn, $moneyNow);
            $playersID = mysqli_real_escape_string($conn, $playersID);
            $updateType = mysqli_real_escape_string($conn, $updateType);
            $result = mysqli_query($conn, "INSERT INTO le_compensation_log(admins_id, admins_playerid, admins_name, money_before, money_given, money_now, update_type, players_id, players_playerid, players_name) VALUES ('$currAccount->id', '$currAccount->playerid', '$currAccount->username', '$moneyBefore', '$moneyGiven', '$moneyNow', '$updateType', '$playersID', '$player->playerid', '$player->name')");
            mysqli_close($conn);
        }

        public function getMoneyAdded(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT SUM(money_given) AS sum FROM le_compensation_log WHERE money_given > 0");
    		mysqli_close($conn);

            return mysqli_fetch_object($result)->sum;
        }

        public function getMoneyTaken(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT SUM(money_given) AS sum FROM le_compensation_log WHERE money_given < 0");
    		mysqli_close($conn);

            return mysqli_fetch_object($result)->sum;
        }
    }
?>

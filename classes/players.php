<?php
    class players{
        public function get_all($page){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM players WHERE uid LIKE '$search' OR name LIKE '%$search%' OR aliases LIKE '%$search%' OR playerid LIKE '$search' LIMIT ". ($page-1) * 30 .", 30");
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
    		$result = mysqli_query($conn, "SELECT * FROM players WHERE uid LIKE '$search' OR name LIKE '%$search%' OR aliases LIKE '%$search%' OR playerid LIKE '$search' ORDER BY timejoined DESC");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function get_all_newplayersdata(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT timejoined FROM players ORDER BY timejoined ASC");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

		public function get_all_playersbrowser($nameSearch){
			$conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT playerid,name,playerid,aliases,bankacc,timejoined,timeupdated FROM players WHERE name LIKE '%$nameSearch%' OR playerid LIKE '$nameSearch'");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
		}

        public function getAllByMoney(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM players ORDER BY bankacc DESC LIMIT 10");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function getAllByCopLvl(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM players WHERE coplevel > 1 ORDER BY coplevel DESC LIMIT 10");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function getMoneySum(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT SUM(`bankacc`) + SUM(`cash`) AS `sum` FROM `players`");
    		mysqli_close($conn);

            return mysqli_fetch_object($result)->sum;
        }

		public function get_by_id($uid){
			$conn = get_mysql_conn();
            $uid = mysqli_real_escape_string($conn, $uid);
    		$result = mysqli_query($conn, "SELECT * FROM players WHERE uid='$uid'");
    		mysqli_close($conn);

            return mysqli_fetch_object($result);
		}

		public function get_by_steamid($steamid){
			$conn = get_mysql_conn();
            $steamid = mysqli_real_escape_string($conn, $steamid);
    		$result = mysqli_query($conn, "SELECT * FROM players WHERE playerid='$steamid'");
    		mysqli_close($conn);

            return mysqli_fetch_object($result);
		}

        public function changeLevel($uid, $type, $level){
            $conn = get_mysql_conn();
    		$uid = mysqli_real_escape_string($conn, $uid);
    		$type = mysqli_real_escape_string($conn, $type);
    		$level = mysqli_real_escape_string($conn, $level);
    		mysqli_query($conn, "UPDATE players SET $type='$level' WHERE uid='$uid'");
    		mysqli_close($conn);
        }

        public function changeMoneyBank($uid, $amount){
            $conn = get_mysql_conn();
    		$uid = mysqli_real_escape_string($conn, $uid);
    		$amount = mysqli_real_escape_string($conn, $amount);
            mysqli_query($conn, "UPDATE players SET bankacc=bankacc+$amount WHERE uid='$uid'");
    		mysqli_close($conn);
        }

        public function changeMoneyCash($uid, $amount){
            $conn = get_mysql_conn();
    		$uid = mysqli_real_escape_string($conn, $uid);
    		$amount = mysqli_real_escape_string($conn, $amount);
            mysqli_query($conn, "UPDATE players SET cash=cash+$amount WHERE uid='$uid'");
    		mysqli_close($conn);
        }

        public function getLicenses($uid, $type){
            if($type == "civ_licenses" || $type == "med_licenses" || $type == "cop_licenses"){
                $conn = get_mysql_conn();
        		$uid = mysqli_real_escape_string($conn, $uid);
        		$type = mysqli_real_escape_string($conn, $type);
                $result = mysqli_query($conn, "SELECT $type FROM players WHERE uid='$uid'");
        		mysqli_close($conn);
                return mysqli_fetch_object($result)->$type;
            }
        }

        public function changeLicenses($uid, $type, $licenses){
            if($type == "civ_licenses" || $type == "med_licenses" || $type == "cop_licenses"){
                $conn = get_mysql_conn();
        		$uid = mysqli_real_escape_string($conn, $uid);
        		$type = mysqli_real_escape_string($conn, $type);
                mysqli_query($conn, "UPDATE players SET $type='$licenses' WHERE uid='$uid'");
        		mysqli_close($conn);
            }
        }

        public function changeGear($uid, $type, $gear){
            if($type == "civ_gear" || $type == "med_gear" || $type == "cop_gear"){
                $conn = get_mysql_conn();
        		$uid = mysqli_real_escape_string($conn, $uid);
        		$type = mysqli_real_escape_string($conn, $type);
                mysqli_query($conn, "UPDATE players SET $type='$gear' WHERE uid='$uid'");
        		mysqli_close($conn);
            }
        }

        public function release_from_jail($uid){
            $conn = get_mysql_conn();
    		$uid = mysqli_real_escape_string($conn, $uid);
            mysqli_query($conn, "UPDATE players SET arrested='0' WHERE uid='$uid'");
    		mysqli_close($conn);
        }
    }
?>

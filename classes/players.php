<?php
    class players{
        public function get_all($page){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM players WHERE uid LIKE ? OR name LIKE ? OR aliases LIKE ? OR ".essentials::getAlias("playerID")." LIKE ? LIMIT ". ($page-1) * 30 .", 30");
            $stmt->execute(array($search, "%$search%", "%$search%", $search));

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function get_all_real(){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM players WHERE uid LIKE '$search' OR name LIKE '%$search%' OR aliases LIKE '%$search%' OR ".essentials::getAlias("playerID")." LIKE '$search' ORDER BY ".essentials::getAlias("timeJoinedAlias")." DESC");
            $stmt->execute(array($search, "%$search%", "%$search%", $search));

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function get_all_newplayersdata(){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT ".essentials::getAlias("timeJoinedAlias")." FROM players ORDER BY ".essentials::getAlias("timeJoinedAlias")." ASC");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

		public function get_all_playersbrowser($nameSearch){
			$conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT ".essentials::getAlias("playerID").",name,aliases,bankacc,".essentials::getAlias("timeJoinedAlias").",".essentials::getAlias("lastPlayedAlias")." FROM players WHERE name LIKE '%$nameSearch%' OR ".essentials::getAlias("playerID")." LIKE '$nameSearch'");
            $stmt->execute(array("%$nameSearch%", $nameSearch));

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
		}

        public function getAllByMoney($startByPoorest = false){
            if($startByPoorest){
                $startByPoorest = "ASC";
            }else{
                $startByPoorest = "DESC";
            }

            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM players ORDER BY bankacc $startByPoorest LIMIT 10");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function getAllByCopLvl(){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM players WHERE coplevel > 1 ORDER BY coplevel DESC LIMIT 10");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function getMoneySum(){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT SUM(`bankacc`) + SUM(`cash`) AS `sum` FROM `players`");
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->sum;
        }

		public function get_by_id($uid){
			$conn = get_mysql_conn();
            $stmt = $conn->prepare("SELECT * FROM players WHERE uid=?");
            $stmt->execute(array($uid));

            return $stmt->fetch(PDO::FETCH_OBJ);
		}

		public function get_by_steamid($steamid){
            $playerIDAlias = essentials::getAlias("playerID");

			$conn = get_mysql_conn();
            $stmt = $conn->prepare("SELECT * FROM players WHERE $playerIDAlias=?");
            $stmt->execute(array($steamid));

            return $stmt->fetch(PDO::FETCH_OBJ);
		}

        public function changeLevel($uid, $type, $level){
            $conn = get_mysql_conn();

            $stmt = $conn->prepare("UPDATE players SET $type=? WHERE uid=?");
            $stmt->execute(array($level, $uid));
        }

        public function changeMoneyBank($uid, $amount){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("UPDATE players SET bankacc=bankacc+$amount WHERE uid=?");
            $stmt->execute(array($uid));
        }

        public function changeMoneyCash($uid, $amount){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("UPDATE players SET cash=cash+$amount WHERE uid=?");
            $stmt->execute(array($uid));
        }

        public function getLicenses($uid, $type){
            if($type == "civ_licenses" || $type == "med_licenses" || $type == "cop_licenses"){
                $conn = get_mysql_conn();
        		$stmt = $conn->prepare("SELECT $type FROM players WHERE uid=?");
                $stmt->execute(array($uid));

                return $stmt->fetch(PDO::FETCH_OBJ)->$type;
            }
        }

        public function changeLicenses($uid, $type, $licenses){
            if($type == "civ_licenses" || $type == "med_licenses" || $type == "cop_licenses"){
                $conn = get_mysql_conn();
        		$stmt = $conn->prepare("UPDATE players SET $type=? WHERE uid=?");
                $stmt->execute(array($licenses, $uid));
            }
        }

        public function changeGear($uid, $type, $gear){
            if($type == "civ_gear" || $type == "med_gear" || $type == "cop_gear"){
                $conn = get_mysql_conn();
        		$stmt = $conn->prepare("UPDATE players SET $type=? WHERE uid=?");
                $stmt->execute(array($gear, $uid));
            }
        }

        public function release_from_jail($uid){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("UPDATE players SET arrested='0' WHERE uid=?");
            $stmt->execute(array($uid));
        }
    }
?>

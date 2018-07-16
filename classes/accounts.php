<?php
    class accounts{
        public function create_db(){
            /*$conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS accounts(
                id int(11) NOT NULL auto_increment,
                username varchar(64) NOT NULL,
                password varchar(64) NOT NULL,
                salt varchar(64) NOT NULL,
                playerid varchar(20) NOT NULL,
                accesslevel int(11) NOT NULL,
                created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                iplist text NOT NULL,
                banned boolean NOT NULL,
            PRIMARY KEY(id), UNIQUE id (id))");
            mysqli_close($conn);*/

            $conn = get_mysql_conn();
            $stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS accounts(
                id int(11) NOT NULL auto_increment,
                username varchar(64) NOT NULL,
                password varchar(64) NOT NULL,
                salt varchar(64) NOT NULL,
                playerid varchar(20) NOT NULL,
                accesslevel int(11) NOT NULL,
                created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                iplist text NOT NULL,
                banned boolean NOT NULL DEFAULT 0,
            PRIMARY KEY(id), UNIQUE id (id))");
            $stmt->execute();
        }

        private function generate_salt(){
            return hash("sha256", bin2hex(openssl_random_pseudo_bytes(32)));
        }

        public function get_all($page){
            $search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();

            $stmt = $conn->prepare("SELECT * FROM accounts WHERE id LIKE ? OR username LIKE ? OR playerid LIKE ? LIMIT ". ($page-1) * 30 .", 30");
            $stmt->execute(array($search, "%$search%", $search));
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            function sorting($a, $b){
				return strcmp(permissions::get_by_id($b->accesslevel)->accesslevel, permissions::get_by_id($a->accesslevel)->accesslevel);
			}

			usort($result, "sorting");

            return $result;
        }

        public function get_all_real(){
            $search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();

            $stmt = $conn->prepare("SELECT * FROM accounts WHERE id LIKE ? OR username LIKE ? OR playerid LIKE ?");
            $stmt->execute(array($search, "%$search%", $search));

    		$result = $stmt->fetchAll(PDO::FETCH_OBJ);

			function sorting($a, $b){
				return strcmp(permissions::get_by_id($b->accesslevel)->accesslevel, permissions::get_by_id($a->accesslevel)->accesslevel);
			}

			usort($result, "sorting");

            return $result;
        }

        public function get_all_by_accesslevel($accesslevel){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM accounts WHERE accesslevel=?");
            $stmt->execute(array($accesslevel));
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function get_by_id($id){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM accounts WHERE id=?");
            $stmt->execute(array($id));
            return $stmt->fetch(PDO::FETCH_OBJ);
        }

        public function get_by_sessionid($sessionid){
            return @self::get_by_id(sessions::get_session($sessionid)->accountid);
        }

        public function get_by_username($username, $caseSensitive=true){
            $conn = get_mysql_conn();
            if($caseSensitive){
                $stmt = $conn->prepare("SELECT * FROM accounts WHERE username=?");
            }else{
                $username = strtolower($username);
                $stmt = $conn->prepare("SELECT * FROM accounts WHERE LOWER(username)=?");
            }
            $stmt->execute(array($username));
            return $stmt->fetch(PDO::FETCH_OBJ);
        }

        public function create($username, $password, $accesslevel, $playerid){
            $conn = get_mysql_conn();
            $salt = self::generate_salt();
            $password = hash("sha256", "$password");
            $password = hash("sha256", "$password:$salt");
            $stmt = $conn->prepare("INSERT INTO accounts(username, password, salt, accesslevel, playerid, iplist) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute(array($username, $password, $salt, $accesslevel, $playerid, "[]"));
            $createdID = $conn->lastInsertId();
            return $createdID;
        }

        public function is_logged_in(){
            if(isset($_SESSION["sessionid"])){
                $is_correct = isset(self::get_by_sessionid($_SESSION["sessionid"])->id);
                if(!$is_correct){
                    unset($_SESSION["sessionid"]);
                }
                return $is_correct;
            }else{
                return false;
            }
        }

        public function get_current_account(){
            if(self::is_logged_in()){
                return self::get_by_sessionid($_SESSION["sessionid"]);
            }
        }

        public function login($username, $password, $rememberme){
            $conn = get_mysql_conn();

            $account = null;
            if(isset(self::get_by_username($username)->id)){
                $account = self::get_by_username($username);
            }else{
                logs::add_log("login", "$1 tried to login with non-existant information: [username:$username], [password:*****]");
                return "No account found by that username!";
            }

            if($account != null){
                if(hash("sha256", "$password:$account->salt") == $account->password){
                    $sessionID = sessions::get_by_id(sessions::add_session($account->id))->sessionid;
                    $_SESSION["sessionid"] = $sessionID;
                    $_SESSION["permissionsUpdateInterval"] = 0;
					$_SESSION["permissions"] = [];
                    $_SESSION["keepSession"] = $rememberme;
                    $_SESSION["lastChatView"] = 8^9;
                    $_SESSION["lastChatType"] = 8^9;

                    $stmt = $conn->prepare("UPDATE accounts SET lastactive=? WHERE id=?");
                    $stmt->execute(array(time(), $account->id));
                    self::add_user_iplist($account->id);
                    logs::add_log("login", "$1 logged in");
                    return "success";
                }else{
                    //wrong password
                    logs::add_log("login", "$1 tried to login to [username:$username] with the wrong [password:*****]", 1, $account->id);
                    return "Wrong password!";
                }
            }else{
                //no account exists?!
                logs::add_log("login", "$1 tried to login with non-existant information: [username:$username], [password:*****]");
                return "No account exists by that username/password!";
            }
        }

        public function get_iplist($id){
    		$conn = get_mysql_conn();
            $stmt = $conn->prepare("SELECT iplist FROM accounts WHERE id=?");
            $stmt->execute(array($id));
    		$iplistArray = json_decode($stmt->fetch(PDO::FETCH_OBJ)->iplist);

    		if(gettype($iplistArray) == "NULL"){
    			return array();
    		}else{
    			return $iplistArray;
    		}
    	}

        public function add_user_iplist($id){
    		$iplistArray = self::get_iplist($id);
    		$isAlreadyStored = false;

    		foreach($iplistArray as $key=>$value){
    			if($value->ip == $_SERVER['REMOTE_ADDR']){
    				$isAlreadyStored = true;
    				$value->lastseen = time();
                    if(!empty($value->timesused)){
                        $value->timesused = $value->timesused + 1;
                    }else{
                        $value->timesused = 1;
                    }
    			}
    		}

    		if($isAlreadyStored == false){
    			array_push($iplistArray, array(
    				"ip" => $_SERVER['REMOTE_ADDR'],
    				"firstseen" => time(),
    				"lastseen" => time(),
    				"timesused" => 1,
    			));
    		}

    		$iplistArray = json_encode($iplistArray);

    		$conn = get_mysql_conn();
            $stmt = $conn->prepare("UPDATE accounts SET iplist=? WHERE id=?");
            $stmt->execute(array($iplistArray, $id));
    	}

        public function does_list_contain_ip($list, $ip){
    		foreach($ip as $value){
    			foreach($list as $value2){
    				if($value2->ip == $value->ip){
    					return true;
    				}
    			}
    		}
    		return false;
    	}

        public function get_all_with_ip($id, $exempid=0){
            $returnlist = array();
            $iplist = self::get_iplist($id);
            foreach(self::get_all_real() as $value){
    			if($value){
    				if($exempid > 0){
    					if($exempid == $value->id){
    						continue;
    					}
    				}
    				if(self::does_list_contain_ip(self::get_iplist($value->id), $iplist)){
    					$returnlist[] = $value;
    				}
    			}
    		}
    		return $returnlist;
        }

        public function changeAccessLevel($id, $accesslevel){
			$currAccount = self::get_current_account();

			$conn = get_mysql_conn();
    		$stmt = $conn->prepare("UPDATE accounts SET accesslevel=? WHERE id=?");
            $stmt->execute(array($accesslevel, $id));
		}

        public function toggleBan($id){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("UPDATE accounts SET banned=!banned WHERE id=?");
    		$stmt->execute(array($id));
        }

        public function changePlayerID($id, $playerid){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("UPDATE accounts SET playerid=? WHERE id=?");
    		$stmt->execute(array($playerid, $id));
        }

        public function changeUsername($id, $username){
			$conn = get_mysql_conn();
            $stmt = $conn->prepare("UPDATE accounts SET username=? WHERE id=?");
            $stmt->execute(array($username, $id));
		}

        public function changePassword($id, $password){
			$currAccount = self::get_by_id($id);

			$password = hash("sha256", "$password:$currAccount->salt");
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("UPDATE accounts SET password=? WHERE id=?");
    		$stmt->execute(array($password, $id));
		}
    }
?>

<?php
    class accounts{
        public function create_db(){
            $conn = get_mysql_conn();
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
            mysqli_close($conn);
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
    		$result = mysqli_query($conn, "SELECT * FROM accounts WHERE id LIKE '$search' OR username LIKE '%$search%' OR playerid LIKE '$search' LIMIT ". ($page-1) * 30 .", 30");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));
			$array = array_filter($array);

			function sorting($a, $b){
				return strcmp(permissions::get_by_id($b->accesslevel)->accesslevel, permissions::get_by_id($a->accesslevel)->accesslevel);
			}

			usort($array, "sorting");

            return $array;
        }

        public function get_all_real(){
            $search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM accounts WHERE id LIKE '$search' OR username LIKE '%$search%' OR playerid LIKE '$search'");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

			function sorting($a, $b){
				return strcmp(permissions::get_by_id($b->accesslevel)->accesslevel, permissions::get_by_id($a->accesslevel)->accesslevel);
			}

			usort($array, "sorting");

            return $array;
        }

        public function get_all_by_accesslevel($accesslevel){
            $conn = get_mysql_conn();
			$accesslevel = mysqli_real_escape_string($conn, $accesslevel);
    		$result = mysqli_query($conn, "SELECT * FROM accounts WHERE accesslevel='$accesslevel'");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function get_by_id($id){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM accounts WHERE id='$id'");
    		mysqli_close($conn);

    		return mysqli_fetch_object($result);
        }

        public function get_by_sessionid($sessionid){
            return @self::get_by_id(sessions::get_session($sessionid)->accountid);
        }

        public function get_by_username($username, $caseSensitive=true){
            $conn = get_mysql_conn();
    		$username = mysqli_real_escape_string($conn, $username);
            if($caseSensitive){
                $result = mysqli_query($conn, "SELECT * FROM accounts WHERE username='$username'");
            }else{
                $username = strtolower($username);
                $result = mysqli_query($conn, "SELECT * FROM accounts WHERE LOWER(username)='$username'");
            }
    		mysqli_close($conn);

    		return mysqli_fetch_object($result);
        }

        public function create($username, $password, $accesslevel, $playerid){
            $conn = get_mysql_conn();
            $salt = self::generate_salt();
            $username = mysqli_real_escape_string($conn, $username);
            $password = mysqli_real_escape_string($conn, $password);
            $password = hash("sha256", "$password");
            $password = hash("sha256", "$password:$salt");
            $accesslevel = mysqli_real_escape_string($conn, $accesslevel);
            $playerid = mysqli_real_escape_string($conn, $playerid);
            mysqli_query($conn, "INSERT INTO accounts(username, password, salt, accesslevel, playerid) VALUES ('$username', '$password', '$salt', '$accesslevel', '$playerid')");
            $createdID = mysqli_insert_id($conn);
            mysqli_close($conn);
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
            $username = mysqli_real_escape_string($conn, $username);
            $password = mysqli_real_escape_string($conn, $password);

            $account = null;
            if(self::get_by_username($username)){
                $account = self::get_by_username($username);
            }else{
                mysqli_close($conn);
                logs::add_log("login", "$1 tried to login with non-existant information: [username:$username], [password:$password]");
                return "No account found by that username!";
            }

            if($account != null){
                if(hash("sha256", "$password:$account->salt") == $account->password){
                    $sessionID = sessions::get_by_id(sessions::add_session($account->id))->sessionid;
                    $_SESSION["sessionid"] = $sessionID;
                    $_SESSION["permissionsUpdateInterval"] = 0;
					$_SESSION["permissions"] = [];
                    $_SESSION["keepSession"] = $rememberme;
                    if($rememberme){
                        setcookie("zeroforumsv2_" . get_config()["cookie_id"], $_COOKIE["zeroforumsv2_" . get_config()["cookie_id"]], time() + 86400);
                    }
					mysqli_query($conn, "UPDATE accounts SET lastactive='" . time() . "' WHERE id='$account->id'");
                    mysqli_close($conn);
                    self::add_user_iplist($account->id);
                    logs::add_log("login", "$1 logged in");
                    return "success";
                }else{
                    //wrong password
                    mysqli_close($conn);
                    logs::add_log("login", "$1 tried to login to [username:$username] with the wrong [password:*****]", 1, $account->id);
                    return "Wrong password!";
                }
            }else{
                //no account exists?!
                mysqli_close($conn);
                logs::add_log("login", "$1 tried to login with non-existant information: [username:$username], [password:*****]");
                return "No account exists by that username/password!";
            }
        }

        public function get_iplist($id){
    		$conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT iplist FROM accounts WHERE id='$id'");
    		mysqli_close($conn);
    		$iplistArray = json_decode(mysqli_fetch_object($result)->iplist);

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
    		$id = mysqli_real_escape_string($conn, $id);
    		mysqli_query($conn, "UPDATE accounts SET iplist='$iplistArray' WHERE id='$id'");
    		mysqli_close($conn);
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
    		$id = mysqli_real_escape_string($conn, $id);
    		$accesslevel = mysqli_real_escape_string($conn, $accesslevel);
    		mysqli_query($conn, "UPDATE accounts SET accesslevel='$accesslevel' WHERE id='$id'");
    		mysqli_close($conn);
		}

        public function toggleBan($id){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		mysqli_query($conn, "UPDATE accounts SET banned=!banned WHERE id='$id'");
    		mysqli_close($conn);
        }

        public function changePlayerID($id, $playerid){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$playerid = mysqli_real_escape_string($conn, $playerid);
    		mysqli_query($conn, "UPDATE accounts SET playerid='$playerid' WHERE id='$id'");
    		mysqli_close($conn);
        }

        public function changeUsername($id, $username){
			$conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$username = mysqli_real_escape_string($conn, $username);
    		mysqli_query($conn, "UPDATE accounts SET username='$username' WHERE id='$id'");
    		mysqli_close($conn);
		}

        public function changePassword($password){ //Not asking for ID because we don't want someone editing someone else's password
			$currAccount = self::get_current_account();

			$newPassword = hash("sha256", "$password:$currAccount->salt");
			$conn = get_mysql_conn();
    		$newPassword = mysqli_real_escape_string($conn, $newPassword);
    		mysqli_query($conn, "UPDATE accounts SET password='$newPassword' WHERE id='$currAccount->id'");
    		mysqli_close($conn);
		}
    }
?>

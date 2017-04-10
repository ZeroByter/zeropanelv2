<?php
    class playernotes{
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

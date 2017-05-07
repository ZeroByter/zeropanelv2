<?php
    class staffchat{
		public function create_db(){
            $conn = get_mysql_conn();
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS panel_staff_chat(
                id int(11) NOT NULL auto_increment,
                sender int(11) NOT NULL,
                message varchar(160) NOT NULL,
                viewers text NOT NULL,
                time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id), UNIQUE id (id))");
            mysqli_close($conn);
        }

        public function does_exist(){
            $conn = get_mysql_conn();
            $exists = mysqli_query($conn, "SELECT 1 FROM panel_staff_chat LIMIT 1");
            mysqli_close($conn);

            return $exists;
        }

        public function get_all(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM panel_staff_chat ORDER BY time ASC");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function create($message){
            $currAccount = accounts::get_current_account();

            $conn = get_mysql_conn();
            $message = mysqli_real_escape_string($conn, $message);
            $result = mysqli_query($conn, "INSERT INTO panel_staff_chat(sender, message, viewers, time) VALUES ('$currAccount->id', '$message', '[]', CURRENT_TIMESTAMP)");
            mysqli_close($conn);
        }
    }
?>

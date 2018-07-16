<?php
    class staffchat{
		public function create_db(){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS panel_staff_chat(
                id int(11) NOT NULL auto_increment,
                sender int(11) NOT NULL,
                message varchar(160) NOT NULL,
                viewers text NOT NULL,
                time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id), UNIQUE id (id))");
            $stmt->execute(array());
        }

        public function does_exist(){
            try {
                $conn = get_mysql_conn();
                $stmt = $conn->prepare("SELECT 1 FROM panel_staff_chat LIMIT 1");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_OBJ);
            }catch(Exception $e){
                return FALSE;
            }

            return $result !== FALSE;
        }

        public function get_all(){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM panel_staff_chat ORDER BY time ASC");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function create($message){
            $currAccount = accounts::get_current_account();

            $conn = get_mysql_conn();
            $stmt = $conn->prepare("INSERT INTO panel_staff_chat(sender, message, viewers) VALUES (?, ?, ?)");
            $stmt->execute(array($currAccount->id, $message, "[]"));
        }
    }
?>

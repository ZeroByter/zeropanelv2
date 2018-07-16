<?php
    class vehicles{
        public function get_all($page){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM vehicles WHERE id LIKE ? OR classname LIKE ? OR pid LIKE ? LIMIT ". ($page-1) * 30 .", 30");
            $stmt->execute(array($search, "%$search%", $search));

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function get_all_real(){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM vehicles WHERE id LIKE ? OR classname LIKE ? OR pid LIKE ?");
            $stmt->execute(array($search, "%$search%", $search));

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function getAllDestroyed(){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("SELECT * FROM vehicles WHERE alive=0");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

        public function get_by_owner($owner, $limit = 99999){
            $conn = get_mysql_conn();
            $stmt = $conn->prepare("SELECT * FROM vehicles WHERE pid=? LIMIT $limit");
            $stmt->execute(array($owner));

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return array_filter($result);
        }

		public function get_by_id($id){
			$conn = get_mysql_conn();
            $stmt = $conn->prepare("SELECT * FROM vehicles WHERE id=?");
            $stmt->execute(array($id));

            return $stmt->fetch(PDO::FETCH_OBJ);
		}

        public function changeInventory($id, $inventory){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("UPDATE vehicles SET inventory=? WHERE id=?");
            $stmt->execute(array($inventory, $id));
        }

        public function store($id){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("UPDATE vehicles SET alive='1',active='0' WHERE id=?");
            $stmt->execute(array($id));
        }

        public function delete($id){
            $conn = get_mysql_conn();
    		$stmt = $conn->prepare("DELETE FROM vehicles WHERE id=?");
            $stmt->execute(array($id));
        }
    }
?>

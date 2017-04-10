<?php
    class vehicles{
        public function get_all($page){
			$search = "";
			if(isset($_GET["search"])){
				$search = $_GET["search"];
			}

            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM vehicles WHERE id LIKE '$search' OR classname LIKE '%$search%' OR pid LIKE '$search' LIMIT ". ($page-1) * 30 .", 30");
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
    		$result = mysqli_query($conn, "SELECT * FROM vehicles WHERE id LIKE '$search' OR classname LIKE '%$search%' OR pid LIKE '$search'");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }
        public function getAllDestroyed(){
            $conn = get_mysql_conn();
    		$result = mysqli_query($conn, "SELECT * FROM vehicles WHERE alive=0");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

        public function get_by_owner($owner, $limit = 99999){
            $conn = get_mysql_conn();
            $owner = mysqli_real_escape_string($conn, $owner);
            $limit = mysqli_real_escape_string($conn, $limit);
    		$result = mysqli_query($conn, "SELECT * FROM vehicles WHERE pid='$owner' LIMIT $limit");
    		mysqli_close($conn);
            while($array[] = mysqli_fetch_object($result));

            return array_filter($array);
        }

		public function get_by_id($id){
			$conn = get_mysql_conn();
            $id = mysqli_real_escape_string($conn, $id);
    		$result = mysqli_query($conn, "SELECT * FROM vehicles WHERE id='$id'");
    		mysqli_close($conn);

            return mysqli_fetch_object($result);
		}

        public function changeInventory($id, $inventory){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		$inventory = mysqli_real_escape_string($conn, $inventory);
    		mysqli_query($conn, "UPDATE vehicles SET inventory='$inventory' WHERE id='$id'");
    		mysqli_close($conn);
        }

        public function store($id){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		mysqli_query($conn, "UPDATE vehicles SET alive='1',active='0' WHERE id='$id'");
    		mysqli_close($conn);
        }

        public function delete($id){
            $conn = get_mysql_conn();
    		$id = mysqli_real_escape_string($conn, $id);
    		mysqli_query($conn, "DELETE FROM vehicles WHERE id='$id'");
    		mysqli_close($conn);
        }
    }
?>

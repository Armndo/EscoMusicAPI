<?php
	include_once("Connection.php");

	class User {

		private $id;
		private $email;
		private $password;
		private $name;

		public function put($id, $email, $password, $name) {
			$this->id = $id;
			$this->email = $email;
			$this->password = $password;
			$this->name = $name;
		}

		private function arraylize() {
			return ["id" => $this->id, "email" => $this->email, "name" => $this->name];
		}

		public function getId() {
			return $this->id;
		}

		public function getEmail() {
			return $this->email;
		}

		public function getName() {
			return $this->name;
		}

		public static function verify($email, $password) {
			$con = new Connection();
			$con = $con->getConnection();
			$sql = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
			$rs = $con->query($sql);
			$user = new User();
			
			if($flag = $rs->num_rows > 0) {
				while($row = $rs->fetch_assoc()) {
					$user->put($row["id"], $row["email"], $row["password"], $row["name"]);
				}
			}

			$con->close();

			return [$flag, $user->arraylize()];
		}

	}
?>
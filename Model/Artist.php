<?php
	include_once("Connection.php");

	class Artist {

		private $id;
		private $name;
		private $birthday;
		private $gender;
    private $country;
		private $years_active;

		public function put($id, $name, $birthday, $gender, $country, $years_active) {
			$this->id = $id;
			$this->name = $name;
			$this->birthday = $birthday;
			$this->gender = $gender;
			$this->country = $country;
			$this->years_active = $years_active;
    }
    
    public function charge($name, $birthday, $gender, $country, $years_active) {
			$this->name = $name;
			$this->birthday = $birthday;
			$this->gender = $gender;
			$this->country = $country;
			$this->years_active = $years_active;
    }

		private function arraylize() {
			return ["id" => $this->id, "name" => $this->name, "birthday" => $this->birthday, "gender" => $this->gender, "country" => $this->country, "years_active" => $this->years_active];
		}

		public function getId() {
			return $this->id;
		}

		public function getName() {
			return $this->name;
		}

		public function getBirthday() {
			return $this->birthday;
    }

		public function getGender() {
			return $this->gender;
    }

		public function getCountry() {
			return $this->country;
    }

		public function getYears_active() {
			return $this->years_active;
    }
    
    public static function get() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM artist";
      $rs = $con->query($sql);
      $artists = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $artist = new Artist();
          $artist->put($row["id"], $row["name"], $row["birthday"], $row["gender"], $row["country"], $row["years_active"]);
          $artists[] = $artist->arraylize();
        }

      $con->close();
      
      return $artists;
    }

    public function commit() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $birthday = $this->birthday == "" ? null : $this->birthday;
      $gender = $this->gender == "" ? null : $this->gender;
      $country = $this->country == "" ? null : $this->country;
      $years_active = $this->years_active == "" ? null : $this->years_active;
      $sql = "INSERT INTO artist (name, birthday, gender, country, years_active) VALUES (?, ?, ?, ?, ?)";
      $aux = $con->prepare($sql);
      $aux->bind_param("sssss", $this->name, $birthday, $gender, $country, $years_active);
      $aux->execute();
      $this->id = $con->insert_id;
      echo $con->error;
      $con->close();
    }

	}
?>
<?php
	include_once("Connection.php");

	class Genre {

		private $id;
		private $genre;
		private $description;

		public function put($id, $genre, $description) {
			$this->id = $id;
			$this->genre = $genre;
			$this->description = $description;
		}

		private function arraylize() {
			return ["id" => $this->id, "genre" => $this->genre, "description" => $this->description];
		}

		public function getId() {
			return $this->id;
		}

		public function getGenre() {
			return $this->genre;
		}

		public function getDescription() {
			return $this->description;
    }
    
    public static function get() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM genre";
      $rs = $con->query($sql);
      $genres = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $genre = new genre();
          $genre->put($row["id"], $row["genre"], $row["description"]);
          $genres[] = $genre->arraylize();
        }

      $con->close();
      
      return $genres;
    }

	}
?>
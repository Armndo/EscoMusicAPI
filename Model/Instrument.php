<?php
	include_once("Connection.php");

	class Instrument {

		private $id;
		private $instrument;
		private $description;

		public function put($id, $instrument, $description) {
			$this->id = $id;
			$this->instrument = $instrument;
			$this->description = $description;
		}

		private function arraylize() {
			return ["id" => $this->id, "instrument" => $this->instrument, "description" => $this->description];
		}

		public function getId() {
			return $this->id;
		}

		public function getInstrument() {
			return $this->instrument;
		}

		public function getDescription() {
			return $this->description;
    }
    
    public static function get() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM instrument";
      $rs = $con->query($sql);
      $instruments = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $instrument = new instrument();
          $instrument->put($row["id"], $row["instrument"], $row["description"]);
          $instruments[] = $instrument->arraylize();
        }

      $con->close();
      
      return $instruments;
    }

	}
?>
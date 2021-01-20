<?php
	include_once("Connection.php");

	class Instrument {

		private $id;
		private $instrument;
		private $description;

    public function find($id) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM instrument WHERE id = " . $id;
      $rs = $con->query($sql);
      if ($rs->num_rows > 0) {
          $row = $rs->fetch_assoc();
          $this->id = $row["id"];
          $this->instrument = $row["instrument"];
          $this->description = $row["description"];
      }
      $con->close();
    }

    public static function random() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM instrument order by rand() limit 1";
      $rs = $con->query($sql);
      $instrument = [];

      if ($rs->num_rows > 0) {
          $instrument = new Instrument();
          $row = $rs->fetch_assoc();
          $instrument->put($row["id"], $row["instrument"], $row["description"]);
          $instrument = $instrument->arraylize();
      }

      $con->close();
      return $instrument;
    }

		public function put($id, $instrument, $description) {
			$this->id = $id;
			$this->instrument = $instrument;
			$this->description = $description;
		}

		public function charge($instrument, $description) {
			$this->instrument = $instrument;
			$this->description = $description;
		}

		public function arraylize() {
			return ["id" => $this->id, "instrument" => $this->instrument, "description" => $this->description, "artists" => $this->artists()];
		}

		public function arraylize_no_recursive() {
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
          $instrument = new Instrument();
          $instrument->put($row["id"], $row["instrument"], $row["description"]);
          $instruments[] = $instrument->arraylize();
        }

      $con->close();
      
      return $instruments;
    }
    
    public static function get_exclusive() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM instrument";
      $rs = $con->query($sql);
      $instruments = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $instrument = new Instrument();
          $instrument->put($row["id"], $row["instrument"], $row["description"]);
          $instruments[] = $instrument->arraylize_no_recursive();
        }

      $con->close();
      
      return $instruments;
    }
    
    public static function search($keyword) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM instrument where instrument like '%$keyword%'";
      $rs = $con->query($sql);
      $instruments = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $instrument = new Instrument();
          $instrument->put($row["id"], $row["instrument"], $row["description"]);
          $instruments[] = $instrument->arraylize();
        }

      $con->close();
      
      return $instruments;
    }

    public function commit() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $description = $this->description == "" ? null : $this->description;
      $sql = "INSERT INTO instrument (instrument, description) VALUES (?, ?)";
      $aux = $con->prepare($sql);
      $aux->bind_param("ss", $this->instrument, $description);
      $aux->execute();
      $this->id = $con->insert_id;
      $con->close();
    }
    
    public function update($instrument, $description) {
      $con = new Connection();
      $con = $con->getConnection();
      $description = $description == "" ? null : $description;
      $sql = "update instrument set instrument = ?, description = ? where id = $this->id";
      $aux = $con->prepare($sql);
      $aux->bind_param("ss", $instrument, $description);
      $aux->execute();
      $con->close();
    }
    
    public function destroy() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "DELETE FROM instrument WHERE id = " . $this->id;
      $con->query($sql);
      $con->close();
    }

    private function artists() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from artist_instrument join artist where artist_id = id and instrument_id = $this->id order by rand() limit 5";
      $rs = $con->query($sql);
      $artists = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $artist = new Artist();
          $artist->put($row["id"], $row["name"], $row["birthday"], $row["gender"], $row["country"], $row["years_active"]);
          $artists[] = $artist->arraylize_no_recursive();
        }

      $con->close();
      
      return $artists;
    }

	}
?>
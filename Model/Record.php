<?php
	include_once("Connection.php");

	class Record {

		private $id;
		private $record;
		private $funded;
    private $country;
		private $founder;

    public function find($id) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM record WHERE id = " . $id;
      $rs = $con->query($sql);
      if ($rs->num_rows > 0) {
          $row = $rs->fetch_assoc();
          $this->id = $row["id"];
          $this->record = $row["record"];
          $this->funded = $row["funded"];
          $this->country = $row["country"];
          $this->founder = $row["founder"];
      }
      $con->close();
    }

    public static function random() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM record order by rand() limit 1";
      $rs = $con->query($sql);
      $record = [];

      if ($rs->num_rows > 0) {
          $record = new Record();
          $row = $rs->fetch_assoc();
          $record->put($row["id"], $row["record"], $row["funded"], $row["country"], $row["founder"]);
          $record = $record->arraylize();
      }

      $con->close();
      return $record;
    }

		public function put($id, $record, $funded, $country, $founder) {
			$this->id = $id;
			$this->record = $record;
			$this->funded = $funded;
			$this->country = $country;
			$this->founder = $founder;
    }
    
    public function charge($record, $funded, $country, $founder) {
			$this->record = $record;
			$this->funded = $funded;
			$this->country = $country;
			$this->founder = $founder;
    }

		public function arraylize() {
			return ["id" => $this->id, "record" => $this->record, "funded" => $this->funded, "country" => $this->country, "founder" => $this->founder, "albums" => $this->albums()];
    }
    
		public function arraylize_no_recursive() {
			return ["id" => $this->id, "record" => $this->record, "funded" => $this->funded, "country" => $this->country, "founder" => $this->founder];
		}

		public function getId() {
			return $this->id;
		}

		public function getRecord() {
			return $this->record;
		}

		public function getFunded() {
			return $this->funded;
    }

		public function getCountry() {
			return $this->country;
    }

		public function getFounder() {
			return $this->founder;
    }
    
    public static function get() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM record";
      $rs = $con->query($sql);
      $records = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $record = new Record();
          $record->put($row["id"], $row["record"], $row["funded"], $row["country"], $row["founder"]);
          $records[] = $record->arraylize();
        }

      $con->close();
      
      return $records;
    }
    
    public static function get_exclusive() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM record";
      $rs = $con->query($sql);
      $records = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $record = new Record();
          $record->put($row["id"], $row["record"], $row["funded"], $row["country"], $row["founder"]);
          $records[] = $record->arraylize_no_recursive();
        }

      $con->close();
      
      return $records;
    }
    
    public static function search($keyword) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM record where record like '%$keyword%'";
      $rs = $con->query($sql);
      $records = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $record = new Record();
          $record->put($row["id"], $row["record"], $row["funded"], $row["country"], $row["founder"]);
          $records[] = $record->arraylize();
        }

      $con->close();
      
      return $records;
    }

    private function albums() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from record_album join album where id = album_id and record_id = $this->id order by rand() limit 5";
      $rs = $con->query($sql);
      $albums = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $album = new Album();
          $album->put($row["id"], $row["album"], $row["released"], $row["recorded"], $row["length"]);
          $albums[] = $album->arraylize_no_recursive();
        }

      $con->close();
      
      return $albums;
    }

    public function commit() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $funded = $this->funded == "" ? null : $this->funded;
      $country = $this->country == "" ? null : $this->country;
      $founder = $this->founder == "" ? null : $this->founder;
      $sql = "INSERT INTO record (record, funded, country, founder) VALUES (?, ?, ?, ?)";
      $aux = $con->prepare($sql);
      $aux->bind_param("ssss", $this->record, $funded, $country, $founder);
      $aux->execute();
      $this->id = $con->insert_id;
      $con->close();
    }
    
    public function update($record, $funded, $country, $founder) {
      $con = new Connection();
      $con = $con->getConnection();
      $funded = $funded == "" ? null : $funded;
      $country = $country == "" ? null : $country;
      $founder = $founder == "" ? null : $founder;
      $sql = "update record set record = ?, funded = ?, country = ?, founder = ? where id = $this->id";
      $aux = $con->prepare($sql);
      $aux->bind_param("ssss", $record, $funded, $country, $founder);
      $aux->execute();
      $con->close();
    }
    
    public function destroy() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "DELETE FROM record WHERE id = " . $this->id;
      $con->query($sql);
      $con->close();
    }

	}
?>
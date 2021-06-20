<?php
	include_once("Connection.php");
	include_once("Media.php");

	class Album {

		private $id;
		private $album;
		private $released;
    private $recorded;
		private $length;

    public function find($id) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM album WHERE id = " . $id;
      $rs = $con->query($sql);
      if ($rs->num_rows > 0) {
          $row = $rs->fetch_assoc();
          $this->id = $row["id"];
          $this->album = $row["album"];
          $this->released = $row["released"];
          $this->recorded = $row["recorded"];
          $this->length = $row["length"];
      }
      $con->close();
    }

    public static function random() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM album order by rand() limit 1";
      $rs = $con->query($sql);
      $album = [];

      if ($rs->num_rows > 0) {
          $album = new Album();
          $row = $rs->fetch_assoc();
          $album->put($row["id"], $row["album"], $row["released"], $row["recorded"], $row["length"]);
          $album = $album->arraylize();
      }

      $con->close();
      return $album;
    }

		public function put($id, $album, $released, $recorded, $length) {
			$this->id = $id;
			$this->album = $album;
			$this->released = $released;
			$this->recorded = $recorded;
			$this->length = $length;
    }
    
    public function charge($album, $released, $recorded, $length) {
			$this->album = $album;
			$this->released = $released;
			$this->recorded = $recorded;
			$this->length = $length;
    }

		public function arraylize() {
      return ["id" => $this->id, "album" => $this->album, "released" => $this->released, "recorded" => $this->recorded, "length" => $this->length, "genres" => $this->genres(), "records" => $this->records(), "artists" => $this->artists(), "bands" => $this->bands(), "songs" => $this->songs()];
		}

		public function arraylize_no_recursive() {
      return ["id" => $this->id, "album" => $this->album, "released" => $this->released, "recorded" => $this->recorded, "length" => $this->length];
		}

		public function getId() {
			return $this->id;
		}

		public function getAlbum() {
			return $this->album;
		}

		public function getReleased() {
			return $this->released;
    }

		public function getRecorded() {
			return $this->recorded;
    }

		public function getLenght() {
			return $this->length;
    }
    
    public static function get() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM album order by released";
      $rs = $con->query($sql);
      $albums = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $album = new Album();
          $album->put($row["id"], $row["album"], $row["released"], $row["recorded"], $row["length"]);
          $albums[] = $album->arraylize();
        }

      $con->close();
      
      return $albums;
    }
    
    public static function get_exclusive() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM album";
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
    
    public static function search($keyword) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM album where album like '%$keyword%'";
      $rs = $con->query($sql);
      $albums = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $album = new Album();
          $album->put($row["id"], $row["album"], $row["released"], $row["recorded"], $row["length"]);
          $albums[] = $album->arraylize();
        }

      $con->close();
      
      return $albums;
    }

    private function genres() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from album_genre join genre where genre_id = id and album_id = $this->id";
      $rs = $con->query($sql);
      $genres = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $genre = new Genre();
          $genre->put($row["id"], $row["genre"], $row["description"]);
          $genres[] = $genre->arraylize_no_recursive();
        }

      $con->close();
      
      return $genres;
    }

    private function records() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from record_album join record where record_id = id and album_id = $this->id";
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

    private function artists() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from artist_album join artist where artist_id = id and album_id = $this->id";
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

    private function bands() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from band_album join band where band_id = id and album_id = $this->id";
      $rs = $con->query($sql);
      $bands = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $band = new Band();
          $band->put($row["id"], $row["band"], $row["created"], $row["country"], $row["years_active"]);
          $bands[] = $band->arraylize_no_recursive();
        }

      $con->close();
      
      return $bands;
    }

    private function songs() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from song where album_id = $this->id";
      $rs = $con->query($sql);
      $songs = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $song = new Song();
          $song->put($row["id"], $row["song"], $row["lyrics"], $row["released"], $row["recorded"], $row["length"], $row["album_id"]);
          $songs[] = $song->arraylize_no_recursive();
        }

      $con->close();
      
      return $songs;
    }

    public function commit() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $released = $this->released == "" ? null : $this->released;
      $recorded = $this->recorded == "" ? null : $this->recorded;
      $length = $this->length == "" ? null : $this->length;
      $sql = "INSERT INTO album (album, released, recorded, length) VALUES (?, ?, ?, ?)";
      $aux = $con->prepare($sql);
      $aux->bind_param("ssss", $this->album, $released, $recorded, $length);
      $aux->execute();
      $this->id = $con->insert_id;
      $con->close();
    }
    
    public function update($album, $released, $recorded, $length) {
      $con = new Connection();
      $con = $con->getConnection();
      $released = $released == "" ? null : $released;
      $recorded = $recorded == "" ? null : $recorded;
      $length = $length == "" ? null : $length;
      $sql = "update album set album = ?, released = ?, recorded = ?, length = ? where id = $this->id";
      $aux = $con->prepare($sql);
      $aux->bind_param("ssss", $album, $released, $recorded, $length);
      $aux->execute();
      $con->close();
    }
    
    public function destroy() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "DELETE FROM album WHERE id = " . $this->id;
      $con->query($sql);
      $con->close();
    }

    public function bindGenre($genres) {
      if(empty($genres)) {
        return ;
      }

      $con = new Connection();
      $con = $con->getConnection(); 
      foreach($genres as $genre_id) {
        $sql = "INSERT INTO album_genre VALUES (?, ?)";
        $aux = $con->prepare($sql);
        $aux->bind_param("ii", $this->id, $genre_id);
        $aux->execute();
      }
      $con->close();
    }

    public function unbindGenres() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $sql = "DELETE FROM album_genre WHERE album_id = $this->id";
      $con->query($sql);
      $con->close();
    }

    public function bindArtist($artists) {
      if(empty($artists)) {
        return ;
      }

      $con = new Connection();
      $con = $con->getConnection(); 
      foreach($artists as $artist_id) {
        $sql = "INSERT INTO artist_album VALUES (?, ?)";
        $aux = $con->prepare($sql);
        $aux->bind_param("ii", $artist_id, $this->id);
        $aux->execute();
      }
      $con->close();
    }

    public function unbindArtists() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $sql = "DELETE FROM artist_album WHERE album_id = $this->id";
      $con->query($sql);
      $con->close();
    }

    public function bindBand($bands) {
      if(empty($bands)) {
        return ;
      }

      $con = new Connection();
      $con = $con->getConnection(); 
      foreach($bands as $band_id) {
        $sql = "INSERT INTO band_album VALUES (?, ?)";
        $aux = $con->prepare($sql);
        $aux->bind_param("ii", $band_id, $this->id);
        $aux->execute();
      }
      $con->close();
    }

    public function unbindBands() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $sql = "DELETE FROM band_album WHERE album_id = $this->id";
      $con->query($sql);
      $con->close();
    }

    public function bindRecord($records) {
      if(empty($records)) {
        return ;
      }

      $con = new Connection();
      $con = $con->getConnection(); 
      foreach($records as $record_id) {
        $sql = "INSERT INTO record_album VALUES (?, ?)";
        $aux = $con->prepare($sql);
        $aux->bind_param("ii", $record_id, $this->id);
        $aux->execute();
      }
      $con->close();
    }

    public function unbindRecords() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $sql = "DELETE FROM record_album WHERE album_id = $this->id";
      $con->query($sql);
      $con->close();
    }

	}
?>
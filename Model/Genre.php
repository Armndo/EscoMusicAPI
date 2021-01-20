<?php
	include_once("Connection.php");

	class Genre {

		private $id;
		private $genre;
		private $description;

    public function find($id) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM genre WHERE id = " . $id;
      $rs = $con->query($sql);
      if ($rs->num_rows > 0) {
          $row = $rs->fetch_assoc();
          $this->id = $row["id"];
          $this->genre = $row["genre"];
          $this->description = $row["description"];
      }
      $con->close();
    }

		public function put($id, $genre, $description) {
			$this->id = $id;
			$this->genre = $genre;
			$this->description = $description;
		}

		public function charge($genre, $description) {
			$this->genre = $genre;
			$this->description = $description;
		}

		public function arraylize() {
			return ["id" => $this->id, "genre" => $this->genre, "description" => $this->description, "bands" => $this->bands(), "artists" => $this->artists(), "albums" => $this->albums(), "songs" => $this->songs()];
		}

		public function arraylize_no_recursive() {
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
    
    public static function get_exclusive() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM genre";
      $rs = $con->query($sql);
      $genres = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $genre = new genre();
          $genre->put($row["id"], $row["genre"], $row["description"]);
          $genres[] = $genre->arraylize_no_recursive();
        }

      $con->close();
      
      return $genres;
    }

    public function commit() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $description = $this->description == "" ? null : $this->description;
      $sql = "INSERT INTO genre (genre, description) VALUES (?, ?)";
      $aux = $con->prepare($sql);
      $aux->bind_param("ss", $this->genre, $description);
      $aux->execute();
      $this->id = $con->insert_id;
      $con->close();
    }
    
    public function update($genre, $description) {
      $con = new Connection();
      $con = $con->getConnection();
      $description = $description == "" ? null : $description;
      $sql = "update genre set genre = ?, description = ? where id = $this->id";
      $aux = $con->prepare($sql);
      $aux->bind_param("ss", $genre, $description);
      $aux->execute();
      $con->close();
    }
    
    public function destroy() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "DELETE FROM genre WHERE id = " . $this->id;
      $con->query($sql);
      $con->close();
    }

    private function bands() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from band_genre join band where band_id = id and genre_id = $this->id order by rand() limit 5";
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

    private function artists() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from artist_genre join artist where artist_id = id and genre_id = $this->id order by rand() limit 5";
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

    private function albums() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from album_genre join album where id = album_id and genre_id = $this->id order by rand() limit 5";
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

    private function songs() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from song_genre join song where id = song_id and genre_id = $this->id order by rand() limit 5";
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

	}
?>
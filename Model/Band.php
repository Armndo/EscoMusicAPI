<?php
	include_once("Connection.php");

	class Band {

		private $id;
		private $band;
		private $created;
    private $country;
		private $years_active;

    public function find($id) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM band WHERE id = " . $id;
      $rs = $con->query($sql);
      if ($rs->num_rows > 0) {
          $row = $rs->fetch_assoc();
          $this->id = $row["id"];
          $this->band = $row["band"];
          $this->created = $row["created"];
          $this->country = $row["country"];
          $this->years_active = $row["years_active"];
      }
      $con->close();
    }

    public static function random() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM band order by rand() limit 1";
      $rs = $con->query($sql);
      $band = [];

      if ($rs->num_rows > 0) {
          $band = new Band();
          $row = $rs->fetch_assoc();
          $band->put($row["id"], $row["band"], $row["created"], $row["country"], $row["years_active"]);
          $band = $band->arraylize();
      }

      $con->close();
      return $band;
    }

		public function put($id, $band, $created, $country, $years_active) {
			$this->id = $id;
			$this->band = $band;
			$this->created = $created;
			$this->country = $country;
			$this->years_active = $years_active;
    }
    
    public function charge($band, $created, $country, $years_active) {
			$this->band = $band;
			$this->created = $created;
			$this->country = $country;
			$this->years_active = $years_active;
    }

		public function arraylize() {
			return ["id" => $this->id, "band" => $this->band, "created" => $this->created, "country" => $this->country, "years_active" => $this->years_active, "genres" => $this->genres(), "artists" => $this->artists(), "medias" => $this->medias(), "albums" => $this->albums()];
    }
    
		public function arraylize_no_recursive() {
			return ["id" => $this->id, "band" => $this->band, "created" => $this->created, "country" => $this->country, "years_active" => $this->years_active];
		}

		public function getId() {
			return $this->id;
		}

		public function getBand() {
			return $this->band;
		}

		public function getCreated() {
			return $this->created;
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
      $sql = "SELECT * FROM band";
      $rs = $con->query($sql);
      $bands = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $band = new Band();
          $band->put($row["id"], $row["band"], $row["created"], $row["country"], $row["years_active"]);
          $bands[] = $band->arraylize();
        }

      $con->close();
      
      return $bands;
    }
    
    public static function get_exclusive() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM band";
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
    
    public static function search($keyword) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM band where band like '%$keyword%'";
      $rs = $con->query($sql);
      $bands = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $band = new Band();
          $band->put($row["id"], $row["band"], $row["created"], $row["country"], $row["years_active"]);
          $bands[] = $band->arraylize();
        }

      $con->close();
      
      return $bands;
    }

    private function genres() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from band_genre join genre where genre_id = id and band_id = $this->id";
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

    private function artists() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from member join artist where artist_id = id and band_id = $this->id";
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

    private function medias() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from band_media join media where media_id = id and band_id = $this->id";
      $rs = $con->query($sql);
      $medias = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $media = new Media();
          $media->put($row["id"], $row["media"], $row["link"]);
          $medias[] = $media->arraylize();
        }

      $con->close();
      
      return $medias;
    }

    private function albums() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from band_album join album where id = album_id and band_id = $this->id";
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
      $created = $this->created == "" ? null : $this->created;
      $country = $this->country == "" ? null : $this->country;
      $years_active = $this->years_active == "" ? null : $this->years_active;
      $sql = "INSERT INTO band (band, created, country, years_active) VALUES (?, ?, ?, ?)";
      $aux = $con->prepare($sql);
      $aux->bind_param("ssss", $this->band, $created, $country, $years_active);
      $aux->execute();
      $this->id = $con->insert_id;
      $con->close();
    }
    

    public function update($band, $created, $country, $years_active) {
      $con = new Connection();
      $con = $con->getConnection();
      $created = $created == "" ? null : $created;
      $country = $country == "" ? null : $country;
      $years_active = $years_active == "" ? null : $years_active;
      $sql = "update band set band = ?, created = ?, country = ?, years_active = ? where id = $this->id";
      $aux = $con->prepare($sql);
      $aux->bind_param("ssss", $band, $created, $country, $years_active);
      $aux->execute();
      $con->close();
    }
    
    public function destroy() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from band_media join media where media_id = id and band_id = $this->id";
      $rs = $con->query($sql);

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $media = new Media();
          $media->put($row["id"], $row["media"], $row["link"]);
          $media->destroy();
        }
      
      $sql = "DELETE FROM band WHERE id = " . $this->id;
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
        $sql = "INSERT INTO band_genre VALUES (?, ?)";
        $aux = $con->prepare($sql);
        $aux->bind_param("ii", $this->id, $genre_id);
        $aux->execute();
      }
      $con->close();
    }

    public function unbindGenres() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $sql = "DELETE FROM band_genre WHERE band_id = $this->id";
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
        $sql = "INSERT INTO member VALUES (?, ?)";
        $aux = $con->prepare($sql);
        $aux->bind_param("ii", $artist_id, $this->id);
        $aux->execute();
      }
      $con->close();
    }

    public function unbindArtists() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $sql = "DELETE FROM member WHERE band_id = $this->id";
      $con->query($sql);
      $con->close();
    }

    public function bindMedia($medias) {
      if(empty($medias)) {
        return ;
      }

      $con = new Connection();
      $con = $con->getConnection(); 
      foreach($medias as $media_id) {
        $sql = "INSERT INTO band_media VALUES (?, ?)";
        $aux = $con->prepare($sql);
        $aux->bind_param("ii", $this->id, $media_id);
        $aux->execute();
      }
      $con->close();
    }

    public function unbindMedia($medias) {
      $sql = "select * from band_media join media where band_id = $this->id and id = media_id";

      if(!empty($medias)) {
        $ids = "(";

        foreach($medias as $key => $media) {
          $ids .= $media;
          if($key < count($medias)-1) {
            $ids .= ", ";
          }
        }

        $ids .= ")";
        $sql = "select * from band_media join media where band_id = $this->id and id = media_id and media_id not in $ids";
      }

      $con = new Connection();
      $con = $con->getConnection(); 
      $rs = $con->query($sql);

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $media = new Media();
          $media->put($row["id"], $row["media"], $row["link"]);
          $media->destroy();
        }

      $con->close();
    }

	}
?>
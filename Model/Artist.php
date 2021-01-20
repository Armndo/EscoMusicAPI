<?php
	include_once("Connection.php");
	include_once("Media.php");

	class Artist {

		private $id;
		private $name;
		private $birthday;
		private $gender;
    private $country;
		private $years_active;

    public function find($id) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM artist WHERE id = " . $id;
      $rs = $con->query($sql);
      if ($rs->num_rows > 0) {
          $row = $rs->fetch_assoc();
          $this->id = $row["id"];
          $this->name = $row["name"];
          $this->birthday = $row["birthday"];
          $this->gender = $row["gender"];
          $this->country = $row["country"];
          $this->years_active = $row["years_active"];
      }
      $con->close();
    }

    public static function random() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM artist order by rand() limit 1";
      $rs = $con->query($sql);
      $artist = [];

      if ($rs->num_rows > 0) {
          $artist = new Artist();
          $row = $rs->fetch_assoc();
          $artist->put($row["id"], $row["name"], $row["birthday"], $row["gender"], $row["country"], $row["years_active"]);
          $artist = $artist->arraylize();
      }

      $con->close();
      return $artist;
    }

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

		public function arraylize() {
      return ["id" => $this->id, "name" => $this->name, "birthday" => $this->birthday, "gender" => $this->gender, "country" => $this->country, "years_active" => $this->years_active, "genres" => $this->genres(), "instruments" => $this->instruments(), "medias" => $this->medias(), "bands" => $this->bands(), "albums" => $this->albums(), "songs" => $this->songs()];
		}

		public function arraylize_no_recursive() {
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
    
    public static function get_exclusive() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM artist";
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
    
    public static function search($keyword) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM artist where name like '%$keyword%'";
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

    private function genres() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from artist_genre join genre where genre_id = id and artist_id = $this->id";
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

    private function instruments() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from artist_instrument join instrument where instrument_id = id and artist_id = $this->id";
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

    private function medias() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from artist_media join media where media_id = id and artist_id = $this->id";
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

    private function bands() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from member join band where band_id = id and artist_id = $this->id";
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

    private function albums() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from artist_album join album where id = album_id and artist_id = $this->id";
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
      $sql = "select * from songwritter join song where id = song_id and artist_id = $this->id";
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
      $birthday = $this->birthday == "" ? null : $this->birthday;
      $gender = $this->gender == "" ? null : $this->gender;
      $country = $this->country == "" ? null : $this->country;
      $years_active = $this->years_active == "" ? null : $this->years_active;
      $sql = "INSERT INTO artist (name, birthday, gender, country, years_active) VALUES (?, ?, ?, ?, ?)";
      $aux = $con->prepare($sql);
      $aux->bind_param("sssss", $this->name, $birthday, $gender, $country, $years_active);
      $aux->execute();
      $this->id = $con->insert_id;
      $con->close();
    }
    

    public function update($name, $birthday, $gender, $country, $years_active) {
      $con = new Connection();
      $con = $con->getConnection();
      $birthday = $birthday == "" ? null : $birthday;
      $gender = $gender == "" ? null : $gender;
      $country = $country == "" ? null : $country;
      $years_active = $years_active == "" ? null : $years_active;
      $sql = "update artist set name = ?, birthday = ?, gender = ?, country = ?, years_active = ? where id = $this->id";
      $aux = $con->prepare($sql);
      $aux->bind_param("sssss", $name, $birthday, $gender, $country, $years_active);
      $aux->execute();
      $con->close();
    }
    
    public function destroy() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from artist_media join media where media_id = id and artist_id = $this->id";
      $rs = $con->query($sql);

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $media = new Media();
          $media->put($row["id"], $row["media"], $row["link"]);
          $media->destroy();
        }
      
      $sql = "DELETE FROM artist WHERE id = " . $this->id;
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
        $sql = "INSERT INTO artist_genre VALUES (?, ?)";
        $aux = $con->prepare($sql);
        $aux->bind_param("ii", $this->id, $genre_id);
        $aux->execute();
      }
      $con->close();
    }

    public function unbindGenres() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $sql = "DELETE FROM artist_genre WHERE artist_id = $this->id";
      $con->query($sql);
      $con->close();
    }

    public function bindInstrument($instruments) {
      if(empty($instruments)) {
        return ;
      }

      $con = new Connection();
      $con = $con->getConnection(); 
      foreach($instruments as $instrument_id) {
        $sql = "INSERT INTO artist_instrument VALUES (?, ?)";
        $aux = $con->prepare($sql);
        $aux->bind_param("ii", $this->id, $instrument_id);
        $aux->execute();
      }
      $con->close();
    }

    public function unbindInstruments() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $sql = "DELETE FROM artist_instrument WHERE artist_id = $this->id";
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
        $sql = "INSERT INTO artist_media VALUES (?, ?)";
        $aux = $con->prepare($sql);
        $aux->bind_param("ii", $this->id, $media_id);
        $aux->execute();
      }
      $con->close();
    }

    public function unbindMedia($medias) {
      $sql = "select * from artist_media join media where artist_id = $this->id and id = media_id";

      if(!empty($medias)) {
        $ids = "(";

        foreach($medias as $key => $media) {
          $ids .= $media;
          if($key < count($medias)-1) {
            $ids .= ", ";
          }
        }

        $ids .= ")";
        $sql = "select * from artist_media join media where artist_id = $this->id and id = media_id and media_id not in $ids";
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
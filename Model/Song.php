<?php
	include_once("Connection.php");
	include_once("Media.php");

	class Song {

		private $id;
    private $song;
    private $lyrics;
		private $released;
    private $recorded;
		private $length;
		private $album_id;

    public function find($id) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM song WHERE id = " . $id;
      $rs = $con->query($sql);
      if ($rs->num_rows > 0) {
          $row = $rs->fetch_assoc();
          $this->id = $row["id"];
          $this->song = $row["song"];
          $this->lyrics = $row["lyrics"];
          $this->released = $row["released"];
          $this->recorded = $row["recorded"];
          $this->length = $row["length"];
          $this->album_id = $row["album_id"];
      }
      $con->close();
    }

    public static function random() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM song order by rand() limit 1";
      $rs = $con->query($sql);
      $song = [];

      if ($rs->num_rows > 0) {
          $song = new Song();
          $row = $rs->fetch_assoc();
          $song->put($row["id"], $row["song"], $row["lyrics"], $row["released"], $row["recorded"], $row["length"], $row["album_id"]);
          $song = $song->arraylize();
      }

      $con->close();
      return $song;
    }

		public function put($id, $song, $lyrics, $released, $recorded, $length, $album_id) {
			$this->id = $id;
			$this->song = $song;
			$this->lyrics = $lyrics;
			$this->released = $released;
			$this->recorded = $recorded;
			$this->length = $length;
			$this->album_id = $album_id;
    }
    
    public function charge($song, $lyrics, $released, $recorded, $length, $album_id) {
			$this->song = $song;
			$this->lyrics = $lyrics;
			$this->released = $released;
			$this->recorded = $recorded;
			$this->length = $length;
			$this->album_id = $album_id;
    }

		public function arraylize() {
      return ["id" => $this->id, "song" => $this->song, "lyrics" => $this->lyrics, "released" => $this->released, "recorded" => $this->recorded, "length" => $this->length, "album_id" => $this->album_id, "album" => $this->album(), "genres" => $this->genres(), "artists" => $this->artists(), "medias" => $this->medias()];
		}

		public function arraylize_no_recursive() {
      return ["id" => $this->id, "song" => $this->song, "lyrics" => $this->lyrics, "released" => $this->released, "recorded" => $this->recorded, "length" => $this->length, "album_id" => $this->album_id];
		}

		public function getId() {
			return $this->id;
		}

		public function getSong() {
			return $this->song;
		}

		public function getLyrics() {
			return $this->lyrics;
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

		public function getAlbum_id() {
			return $this->album_id;
    }
    
    public static function get() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM song";
      $rs = $con->query($sql);
      $songs = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $song = new Song();
          $song->put($row["id"], $row["song"], $row["lyrics"], $row["released"], $row["recorded"], $row["length"], $row["album_id"]);
          $songs[] = $song->arraylize();
        }

      $con->close();
      
      return $songs;
    }
    
    public static function get_exclusive() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM song";
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
    
    public static function search($keyword) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM song where song like '%$keyword%'";
      $rs = $con->query($sql);
      $songs = [];

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $song = new Song();
          $song->put($row["id"], $row["song"], $row["lyrics"], $row["released"], $row["recorded"], $row["length"], $row["album_id"]);
          $songs[] = $song->arraylize();
        }

      $con->close();
      
      return $songs;
    }

    private function album() {
      if($this->album_id == null) {
        return null;
      }
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from album where id = $this->album_id";
      $rs = $con->query($sql);
      $album = new Album();

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $album->put($row["id"], $row["album"], $row["released"], $row["recorded"], $row["length"]);
        }

      $con->close();
      
      return $album->arraylize_no_recursive();
    }

    private function genres() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from song_genre join genre where genre_id = id and song_id = $this->id";
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
      $sql = "select * from songwritter join artist where artist_id = id and song_id = $this->id";
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
      $sql = "select * from song_media join media where media_id = id and song_id = $this->id";
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

    public function commit() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $released = $this->released == "" ? null : $this->released;
      $lyrics = $this->lyrics == "" ? null : $this->lyrics;
      $recorded = $this->recorded == "" ? null : $this->recorded;
      $length = $this->length == "" ? null : $this->length;
      $album_id = $this->album_id == "" ? null : $this->album_id;
      $sql = "INSERT INTO song (song, lyrics, released, recorded, length, album_id) VALUES (?, ?, ?, ?, ?, ?)";
      $aux = $con->prepare($sql);
      $aux->bind_param("sssssi", $this->song, $lyrics, $released, $recorded, $length, $album_id);
      $aux->execute();
      $this->id = $con->insert_id;
      $con->close();
    }
    
    public function update($song, $lyrics, $released, $recorded, $length, $album_id) {
      $con = new Connection();
      $con = $con->getConnection();
      $lyrics = $lyrics == "" ? null : $lyrics;
      $released = $released == "" ? null : $released;
      $recorded = $recorded == "" ? null : $recorded;
      $length = $length == "" ? null : $length;
      $sql = "update song set song = ?, lyrics = ?, released = ?, recorded = ?, length = ?, album_id = ? where id = $this->id";
      $aux = $con->prepare($sql);
      $aux->bind_param("sssssi", $song, $lyrics, $released, $recorded, $length, $album_id);
      $aux->execute();
      $con->close();
    }
    
    public function destroy() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "select * from song_media join media where media_id = id and song_id = $this->id";
      $rs = $con->query($sql);

      if ($rs->num_rows > 0)
        while($row = $rs->fetch_assoc()) {
          $media = new Media();
          $media->put($row["id"], $row["media"], $row["link"]);
          $media->destroy();
        }

      $sql = "DELETE FROM song WHERE id = " . $this->id;
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
        $sql = "INSERT INTO song_genre VALUES (?, ?)";
        $aux = $con->prepare($sql);
        $aux->bind_param("ii", $this->id, $genre_id);
        $aux->execute();
      }
      $con->close();
    }

    public function unbindGenres() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $sql = "DELETE FROM song_genre WHERE song_id = $this->id";
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
        $sql = "INSERT INTO songwritter VALUES (?, ?)";
        $aux = $con->prepare($sql);
        $aux->bind_param("ii", $artist_id, $this->id);
        $aux->execute();
      }
      $con->close();
    }

    public function unbindArtists() {
      $con = new Connection();
      $con = $con->getConnection(); 
      $sql = "DELETE FROM songwritter WHERE song_id = $this->id";
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
        $sql = "INSERT INTO song_media VALUES (?, ?)";
        $aux = $con->prepare($sql);
        $aux->bind_param("ii", $this->id, $media_id);
        $aux->execute();
      }
      $con->close();
    }

    public function unbindMedia($medias) {
      $sql = "select * from song_media join media where song_id = $this->id and id = media_id";

      if(!empty($medias)) {
        $ids = "(";

        foreach($medias as $key => $media) {
          $ids .= $media;
          if($key < count($medias)-1) {
            $ids .= ", ";
          }
        }

        $ids .= ")";
        $sql = "select * from song_media join media where song_id = $this->id and id = media_id and media_id not in $ids";
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
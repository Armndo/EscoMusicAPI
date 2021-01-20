<?php
	include_once("Connection.php");

	class Media {

		private $id;
		private $media;
		private $link;

    public function find($id) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM media WHERE id = " . $id;
      $rs = $con->query($sql);
      if ($rs->num_rows > 0) {
          $row = $rs->fetch_assoc();
          $this->id = $row["id"];
          $this->media = $row["media"];
          $this->link = $row["link"];
      }
      $con->close();
    }

		public function put($id, $media, $link) {
			$this->id = $id;
			$this->media = $media;
			$this->link = $link;
		}
    
    public function charge($media, $link) {
			$this->media = $media;
			$this->link = $link;
    }

		public function arraylize() {
			return ["id" => $this->id, "media" => $this->media, "link" => $this->link];
		}

		public function getId() {
			return $this->id;
		}

		public function getMedia() {
			return $this->media;
		}

		public function getLink() {
			return $this->link;
		}
    
    public static function get() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "SELECT * FROM media";
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
      $sql = "INSERT INTO media (media, link) VALUES (?, ?)";
      $aux = $con->prepare($sql);
      $aux->bind_param("ss", $this->media, $this->link);
      $aux->execute();
      $this->id = $con->insert_id;
      $con->close();
    }

    public function update($media, $link) {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "UPDATE media SET media = ?, link = ? WHERE id = " . $this->id;
      $aux = $con->prepare($sql);
      $aux->bind_param("ss", $media, $link);
      $aux->execute();
      $con->close();
    }
    
    public function destroy() {
      $con = new Connection();
      $con = $con->getConnection();
      $sql = "DELETE FROM media WHERE id = " . $this->id;
      $con->query($sql);
      $con->close();
    }

	}
?>
<?php
	include_once("../Model/Song.php");
	
  $controller = new SongController($_POST);

	class SongController {

		private $data;

		public function __construct($request) {
			$this->data = json_decode($request["data"], true);
			switch($this->data["action"]) {
				case "create":
					$this->store();
					break;
					case "update":
					$this->update();
					break;
				case "destroy":
					$this->destroy();
					break;
				default:
					break;
			}
    }

		public function store() {
			$song = new Song();
			$song->charge($this->data["song"], $this->data["lyrics"], $this->data["released"], $this->data["recorded"], $this->data["length"], $this->data["album_id"]);
			$song->commit();
			$song->bindGenre($this->data["genre"]);
			$song->bindArtist($this->data["artist"]);
		}

		public function update() {
			$song = new Song();
			$song->find($this->data["id"]);
			$song->update($this->data["song"], $this->data["lyrics"], $this->data["released"], $this->data["recorded"], $this->data["length"], $this->data["album_id"]);
			$song->unbindGenres();
			$song->unbindArtists();
			$song->bindGenre($this->data["genre"]);
			$song->bindArtist($this->data["artist"]);
		}

		public function destroy() {
			$song = new Song();
			$song->find($this->data["id"]);
			$song->destroy();
		}

	}

?>
<?php
	include_once("../Model/Album.php");
	include_once("../Model/Media.php");
	
  $controller = new AlbumController($_POST);

	class AlbumController {

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
			$album = new Album();
			$album->charge($this->data["album"], $this->data["released"], $this->data["recorded"], $this->data["length"]);
			$album->commit();
			$album->bindGenre($this->data["genre"]);
			$album->bindArtist($this->data["artist"]);
			$album->bindBand($this->data["band"]);
		}

		public function update() {
			$album = new Album();
			$album->find($this->data["id"]);
			$album->update($this->data["album"], $this->data["released"], $this->data["recorded"], $this->data["length"]);
			$album->unbindGenres();
			$album->unbindArtists();
			$album->unbindBands();
			$album->bindGenre($this->data["genre"]);
			$album->bindArtist($this->data["artist"]);
			$album->bindBand($this->data["band"]);
		}

		public function destroy() {
			$album = new Album();
			$album->find($this->data["id"]);
			$album->destroy();
		}

	}

?>
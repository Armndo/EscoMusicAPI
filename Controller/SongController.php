<?php
	include_once("../Model/Song.php");
	include_once("../Model/Media.php");
	
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

			$medias = [];
			foreach($this->data["media"] as $media_data) {
				$media = new Media();
				$media->charge($media_data["media"], $media_data["link"]);
				$media->commit();
				$medias[] = $media->getId();
			}

			$song->bindMedia($medias);
		}

		public function update() {
			$song = new Song();
			$song->find($this->data["id"]);
			$medias_to_keep = [];
			$medias_to_bind = [];
			
			foreach($this->data["media"] as $media_data) {
				$media = new Media();
				if(array_key_exists("id", $media_data)) {
					$media->find($media_data["id"]);
					$media->update($media_data["media"], $media_data["link"]);
					$medias_to_keep[] = $media_data["id"];
				} else {
					$media->charge($media_data["media"], $media_data["link"]);
					$media->commit();
					$medias_to_bind[] = $media->getId();
					$medias_to_keep[] = $media->getId();
				}
			}

			$song->update($this->data["song"], $this->data["lyrics"], $this->data["released"], $this->data["recorded"], $this->data["length"], $this->data["album_id"]);
			$song->unbindGenres();
			$song->unbindArtists();
			$song->bindGenre($this->data["genre"]);
			$song->bindArtist($this->data["artist"]);
			$song->bindMedia($medias_to_bind);
			$song->unbindMedia($medias_to_keep);
		}

		public function destroy() {
			$song = new Song();
			$song->find($this->data["id"]);
			$song->destroy();
		}

	}

?>
<?php
	include_once("../Model/Artist.php");
	include_once("../Model/Media.php");
	
  $controller = new ArtistController($_POST);

	class ArtistController {

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
			$artist = new Artist();
			$artist->charge($this->data["name"], $this->data["birthday"], $this->data["gender"], $this->data["country"], $this->data["years_active"]);
			$artist->commit();
			$artist->bindGenre($this->data["genre"]);
			$artist->bindInstrument($this->data["instrument"]);

			$medias = [];
			foreach($this->data["media"] as $media_data) {
				$media = new Media();
				$media->charge($media_data["media"], $media_data["link"]);
				$media->commit();
				$medias[] = $media->getId();
			}

			$artist->bindMedia($medias);
		}

		public function update() {
			$artist = new Artist();
			$artist->find($this->data["id"]);
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
			
			$artist->update($this->data["name"], $this->data["birthday"], $this->data["gender"], $this->data["country"], $this->data["years_active"]);
			$artist->unbindGenres();
			$artist->unbindInstruments();
			$artist->bindGenre($this->data["genre"]);
			$artist->bindInstrument($this->data["instrument"]);
			$artist->bindMedia($medias_to_bind);
			$artist->unbindMedia($medias_to_keep);
		}

		public function destroy() {
			$artist = new Artist();
			$artist->find($this->data["id"]);
			$artist->destroy();
		}

	}

?>
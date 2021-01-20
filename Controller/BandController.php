<?php
	include_once("../Model/Band.php");
	include_once("../Model/Media.php");
	
  $controller = new BandController($_POST);

	class BandController {

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
			$band = new Band();
      $band->charge($this->data["band"], $this->data["created"], $this->data["country"], $this->data["years_active"]);
			$band->commit();
			$band->bindGenre($this->data["genre"]);
			$band->bindArtist($this->data["artist"]);

			$medias = [];
			foreach($this->data["media"] as $media_data) {
				$media = new Media();
				$media->charge($media_data["media"], $media_data["link"]);
				$media->commit();
				$medias[] = $media->getId();
			}

			$band->bindMedia($medias);
		}

		public function update() {
			$band = new Band();
			$band->find($this->data["id"]);
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
			
			$band->update($this->data["band"], $this->data["created"], $this->data["country"], $this->data["years_active"]);
			$band->unbindGenres();
			$band->unbindArtists();
			$band->bindGenre($this->data["genre"]);
			$band->bindArtist($this->data["artist"]);
			$band->bindMedia($medias_to_bind);
			$band->unbindMedia($medias_to_keep);
		}

		public function destroy() {
			$band = new Band();
			$band->find($this->data["id"]);
			$band->destroy();
		}

	}

?>
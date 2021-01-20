<?php
	include_once("../Model/Genre.php");

  $controller = new GenreController($_POST);

	class GenreController {

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
			$genre = new Genre();
			$genre->charge($this->data["genre"], $this->data["description"]);
			$genre->commit();
		}

		public function update() {
			$genre = new Genre();
			$genre->find($this->data["id"]);
			$genre->update($this->data["genre"], $this->data["description"]);
		}

		public function destroy() {
			$genre = new Genre();
			$genre->find($this->data["id"]);
			$genre->destroy();
		}

	}

?>
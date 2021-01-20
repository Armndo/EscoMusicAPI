<?php
	include_once("../Model/Record.php");
	
  $controller = new RecordController($_POST);

	class RecordController {

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
			$record = new Record();
			$record->charge($this->data["record"], $this->data["funded"], $this->data["country"], $this->data["founder"]);
			$record->commit();
		}

		public function update() {
			$record = new Record();
			$record->find($this->data["id"]);
			$record->update($this->data["record"], $this->data["funded"], $this->data["country"], $this->data["founder"]);
		}

		public function destroy() {
			$record = new Record();
			$record->find($this->data["id"]);
			$record->destroy();
		}

	}

?>
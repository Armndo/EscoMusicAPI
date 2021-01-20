<?php
	include_once("../Model/Instrument.php");

  $controller = new InstrumentController($_POST);

	class InstrumentController {

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
			$instrument = new Instrument();
			$instrument->charge($this->data["instrument"], $this->data["description"]);
			$instrument->commit();
		}

		public function update() {
			$instrument = new Instrument();
			$instrument->find($this->data["id"]);
			$instrument->update($this->data["instrument"], $this->data["description"]);
		}

		public function destroy() {
			$instrument = new Instrument();
			$instrument->find($this->data["id"]);
			$instrument->destroy();
		}

	}

?>
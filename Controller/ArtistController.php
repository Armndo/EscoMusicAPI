<?php
	include_once("../Model/Artist.php");
	
  $controller = new ArtistController($_POST);
  $controller->store();

	class ArtistController {

		private $request;

		public function __construct($request) {
			$this->request = $request;
    }

		public function store() {
      // var_dump($this->request);
			$usuario = new Artist();
			$usuario->charge($this->request["name"], $this->request["birthday"], $this->request["gender"], $this->request["country"], $this->request["years_active"]);
			$usuario->commit();
		}

		// public function update() {
		// 	$proveedor = new Proveedor();
		// 	$proveedor->find($this->request["id"]);
		// 	$proveedor->usuario()->update($this->request["password"]);
		// 	$proveedor->update($this->request["razon"], $this->request["giro"]);
		// 	$proveedor->direccion()->update($this->request["calle"], $this->request["numext"], $this->request["numint"], $this->request["estado"], $this->request["municipio"], $this->request["colonia"], $this->request["cp"]);
		// }

		// public function destroy() {
		// 	$proveedor = new Proveedor();
		// 	$proveedor->find($this->request["id"]);
		// 	$proveedor->usuario()->destroy();
		// }

	}

?>
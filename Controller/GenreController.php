<?php
	include_once("../Model/Genre.php");
	
	$controller = new GenreController($_POST);

	class GenreController {

		private $request;

		public function __construct($request) {
			$this->request = $request;
    }

		public function store() {
			$usuario = new Usuario();
			$usuario->charge($this->request["email"], $this->request["password"], "Proveedor");
			$usuario->commit();
			$proveedor = new Proveedor();
			$proveedor->charge($this->request["razon"], $this->request["giro"], $usuario->getId());
			$proveedor->commit();
			$direccion = new Direccion();
			$direccion->charge($this->request["calle"], $this->request["numext"], $this->request["numint"], $this->request["estado"], $this->request["municipio"], $this->request["colonia"], $this->request["cp"], null, $proveedor->getId());
			$direccion->commit();
			if($proveedor->getId() == 0) {
				$usuario->destroy();
				$direccion->destroy();
			}
		}

		public function update() {
			$proveedor = new Proveedor();
			$proveedor->find($this->request["id"]);
			$proveedor->usuario()->update($this->request["password"]);
			$proveedor->update($this->request["razon"], $this->request["giro"]);
			$proveedor->direccion()->update($this->request["calle"], $this->request["numext"], $this->request["numint"], $this->request["estado"], $this->request["municipio"], $this->request["colonia"], $this->request["cp"]);
		}

		public function destroy() {
			$proveedor = new Proveedor();
			$proveedor->find($this->request["id"]);
			$proveedor->usuario()->destroy();
		}

	}

?>
<?php
	include_once("./Model/User.php");

	if($_SERVER['REQUEST_METHOD'] == "POST") {
		header('Content-type: application/json');

		$email = $_POST["email"];
		$password = $_POST["password"];

  	[$auth, $user] = User::verify($email, $password);

  	echo json_encode([
  		"auth" => $auth,
  		"user" => $user,
  	]);
	} else {
		header("HTTP/1.1 405 Method Not Allowed");
	}
?>
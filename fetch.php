<?php
	include_once("./Model/Genre.php");
  include_once("./Model/Instrument.php");
  include_once("./Model/Artist.php");
  
  header('Content-type: application/json');

  $params = [];
  foreach($_REQUEST["params"] as $param) {
    $value = [];
    switch($param) {
      case "genre":
        $value = Genre::get();
        break;
        case "instrument":
          $value = Instrument::get();
          break;
        case "artist":
          $value = Artist::get();
          break;
      default:
        $value = ["ERROR"];
        break;
    }
    $params[$param] = $value;
  }
  
  echo json_encode($params);
?>
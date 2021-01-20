<?php
	include_once("./Model/Genre.php");
  include_once("./Model/Instrument.php");
  include_once("./Model/Artist.php");
  include_once("./Model/Band.php");
  include_once("./Model/Album.php");
  include_once("./Model/Song.php");
  include_once("./Model/Record.php");
  include_once("./Model/Collection.php");
  
	header('Content-Type: application/json; charset=utf-8');

  $params = [];
  if(isset($_REQUEST["params"])) {
    foreach($_REQUEST["params"] as $param) {
      $value = [];
      if(isset($_REQUEST["input"])) {
        switch($param) {
          case "genres":
            $value = Genre::get_exclusive();
            break;
          case "instruments":
            $value = Instrument::get_exclusive();
            break;
          case "artists":
            $value = Artist::get_exclusive();
            break;
          case "bands":
            $value = Band::get_exclusive();
            break;
          case "records":
            $value = Record::get_exclusive();
            break;
          case "albums":
            $value = Album::get_exclusive();
            break;
          case "songs":
            $value = Song::get_exclusive();
            break;
        }
      } else {
        switch($param) {
          case "genres":
            $value = Genre::get();
            break;
          case "instruments":
            $value = Instrument::get();
            break;
          case "artists":
            $value = Artist::get();
            break;
          case "bands":
            $value = Band::get();
            break;
          case "records":
            $value = Record::get();
            break;
          case "albums":
            $value = Album::get();
            break;
          case "songs":
            $value = Song::get();
            break;
          case "genre":
            $value = new Genre();
            $value->find($_REQUEST["genre_id"]);
            $value = $value->arraylize();
            break;
          case "instrument":
            $value = new Instrument();
            $value->find($_REQUEST["instrument_id"]);
            $value = $value->arraylize();
            break;
          case "artist":
            $value = new Artist();
            $value->find($_REQUEST["artist_id"]);
            $value = $value->arraylize();
            break;
          case "band":
            $value = new Band();
            $value->find($_REQUEST["band_id"]);
            $value = $value->arraylize();
            break;
          case "record":
            $value = new Record();
            $value->find($_REQUEST["record_id"]);
            $value = $value->arraylize();
            break;
          case "album":
            $value = new Album();
            $value->find($_REQUEST["album_id"]);
            $value = $value->arraylize();
            break;
          case "song":
            $value = new Song();
            $value->find($_REQUEST["song_id"]);
            $value = $value->arraylize();
            break;
          default:
            $value = ["ERROR"];
            break;
        }
      }
      $params[$param] = $value;
    }
  } else if(isset($_REQUEST["query"])) {
    $collection = new Collection();
    $params = $collection->find($_REQUEST["query"]);
  } else {
    $collection = new Collection();
    $params = $collection->random();
  }
  
  echo json_encode($params);
?>
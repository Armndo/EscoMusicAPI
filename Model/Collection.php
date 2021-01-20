<?php
	include_once("Connection.php");

	class Collection {

		private $artists;
		private $bands;
		private $records;
		private $albums;
		private $songs;
		private $genres;
    private $instruments;

		public function __construct() {
      $this->artists = [];
      $this->bands = [];
      $this->records = [];
      $this->albums = [];
      $this->songs = [];
      $this->genres = [];
      $this->instruments = [];
    }
    
		public function find($keyword) {
      $this->artists = Artist::search($keyword);
      $this->bands = Band::search($keyword);
      $this->records = Record::search($keyword);
      $this->albums = Album::search($keyword);
      $this->songs = Song::search($keyword);
      $this->genres = Genre::search($keyword);
      $this->instruments = Instrument::search($keyword);
      return $this->arraylize();
    }
    
		public function random() {
      $sel = rand(0, 6);
      switch($sel) {
        case 0:
          $this->artists[] = Artist::random();
          break;
        case 1:
          $this->bands[] = Band::random();
          break;
        case 2:
          $this->records[] = Record::random();
          break;
        case 3:
          $this->albums[] = Album::random();
          break;
        case 4:
          $this->songs[] = Song::random();
          break;
        case 5:
          $this->genres[] = Genre::random();
          break;
        case 6:
          $this->instruments[] = Instrument::random();
          break;
        default:
          break;
      }
      return $this->arraylize();
    }

    public function arraylize() {
      return ["artists" => $this->artists, "bands" => $this->bands, "records" => $this->records, "albums" => $this->albums, "songs" => $this->songs, "genres" => $this->genres, "instruments" => $this->instruments];
    }

	}
?>
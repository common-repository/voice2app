<?php
namespace MpVoice2App;
use \PDO;

class PdoExtend extends \PDO {

  private $uname, $pword;
  private static $instance;

  public function __construct() {
    $this->init();
    $opt = [ 
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
          \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
      ];
     $con = parent::__construct($this->dsn, $this->uname, $this->pword,$opt);


  }

  private function init($a = false) {

    $this->dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $this->uname = DB_USER;
    $this->pword = DB_PASSWORD;

//      $this->dsn = "mysql:host=".DATABASE_HOST.";dbname=".DATABASE_NAME; 
//      $this->uname = DATABASE_USERNAME;
//      $this->pword = DATABASE_PASSWORD;
  }

}
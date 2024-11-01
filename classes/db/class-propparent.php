<?php
namespace MpVoice2App;


class PropParent {

  private static $instance = null;
  private static $co = null;

  private function __construct() {
    
  }

  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new PropParent();
      self::$co = new PdoExtend();

      $opt = [
          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
          \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
      ];
      self::$co = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD, $opt);
    }
    
    return self::$instance;
  }

  public function getCo() {
    return self::$co;
  }

}
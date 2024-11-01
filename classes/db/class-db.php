<?php

namespace MpVoice2App;

class Db {

  /**
   * Table columns for items table
   * 
   */
  const CSV_ID = "mp_csv_id";
  const CSV_AUTO_ID = "mp_csv_auto_id";
  const CSV_SECURITY_CODE = "mp_csv_security_code";
  const CSV_C_DATE = "mp_csv_c_date";
  const CSV_E_DATE = "mp_csv_e_date";
  const CSV_IS_ALIVE = "mp_csv_is_alive";
  const CSV_COUNT = "mp_csv_count";
  const CSV_DELETED = "mp_csv_deleted";

  public static $DB_CSV;
  public static $TB_CSV;
  private $db_prefix = "wp_mp_confirm_product_";

  public function __construct() {
    $this->initialize();
    $this->migrations();
  }

  private function initialize() {
    self::$TB_CSV = $this->db_prefix . "csv";

    self::$DB_CSV = new Properties("{$this->db_prefix}csv");
  }

  private function migrations() {
    $this->migration_csv();
  }

  function migration_csv() {
    global $wpdb;
    $sql = "CREATE TABLE `{$this->db_prefix}csv` ("
      . "
              `mp_csv_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `mp_csv_auto_id` varchar(255),
              `mp_csv_security_code` varchar(255),
              `mp_csv_c_date` varchar(255),
              `mp_csv_e_date` varchar(255),
              `mp_csv_is_alive` varchar(255),
              `mp_csv_count` varchar(255),
              `mp_csv_deleted` int(11) NOT NULL 
             "
      . ") ENGINE = InnoDB DEFAULT CHARSET = latin1;
      ";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
  }

  const md = "djijoepwijpihhph";
  const w = "w";
  const h = "h";
  const oa = "orderA";
  const od = "orderD";
  const l = "limit";
  const gt = ">" . self::w;
  const lt = "<" . self::md;
  const orr = "dieopwipifjpoiewjpij";
  const addd = "dkijopeiwpijpiojp4fi39";

  public static $prep, $values, $insertedId;

}

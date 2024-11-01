<?php

namespace MpVoice2App;

class Common {
  /* hold footer scripts */

  public static $HOLD_FOOTER_SCRIPT = [];
  public static $ONLINE_NOW = false;
  public static $PLUGIN_NAME = "";
  public static $PLUGIN_DIR = "";
  public static $PLUGIN_URL = "";
  public static $SCRIPT_VERSION = 2.0;
  public static $ONLINE_COUNT_START = 1;
  public static $ONE = "";
  public static $TWO = "";
  public static $THREE = "";
  public static $FOUR = "";
  public static $FIVE = "";


  /* IMG */
  public static $IMG_BLACK;
  private static $instance = null;

  function __construct() {
    self::$SCRIPT_VERSION = time();
    if ($_SERVER["SERVER_NAME"] === "localhost") {
//      self::$SCRIPT_VERSION = time();
    } else {
      self::$ONLINE_NOW = true;
    }
    if (isset($_SERVER["SERVER_NAME"])) {
      if ($_SERVER["SERVER_NAME"] === "localhost") {
        
      } else {
        self::$ONLINE_COUNT_START = 0;
      }
    }
    $this->init_constants();
    $this->setOnes();
    $this->sendSuccess();
    $this->sendFailure();
  }

  /**
   * getContents Obstart & Loads file & Obclean
   * 
   * @param type $filename
   * @return text file content
   */
  public static function getContents($filename) {
    ob_start();
    require $filename;
    $code = ob_get_clean();
    return $code;
  }

  public function setOnes() {
    if (isset($_SERVER["SERVER_NAME"])) {
      $online_count = ($_SERVER["SERVER_NAME"] === "localhost") ? 1 : 0;
    }
//    Common::in_script(['server' => $_SERVER]);
    if (isset($_SERVER["REDIRECT_URL"])) {
      $slug = $_SERVER['REDIRECT_URL'];
      $expload = explode("/", $slug);
      $c_s = $online_count;
      @$one = $expload[($c_s + 1)];
      @$two = $expload[($c_s + 2)];
      @$three = $expload[($c_s + 3)];
      @$four = $expload[($c_s + 4)];
      @$five = $expload[($c_s + 5)];
//    Common::in_script([
//      'one' => $one,
//      'two' => $two,
//      'three' => $three,
//      'four' => $four,
//      'five' => $five,
//    ]);
    }
  }

  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new Common();
    }
    return self::$instance;
  }

  private function init_constants() {
    self::$PLUGIN_NAME = "/voice2app";
    self::$PLUGIN_DIR = plugin_dir_path(dirname(dirname(__FILE__)));
    self::$PLUGIN_URL = plugin_dir_url( __FILE__ );
    self::$SCRIPT_VERSION = time();
  }

  public static function in_script($data_to_assign, string $var_name = null, $echo_in_footer = false) {
    if ($var_name === null) {
      $var_name = "mppr_" . rand(443, 4857839);
    }
// var_dump($data_to_assign);
    $ff = "<script>";
    $ff .= " var " . $var_name . " = " . json_encode($data_to_assign) . "; ";
    $ff .= " console.log(" . $var_name . "); ";
    $ff .= "</script>";
    if ($echo_in_footer === TRUE) {
      self::$HOLD_FOOTER_SCRIPT = " " . $ff . " ";
    } else {
      echo $ff;
    }
  }

  public static function eko($data, $message = "") {
    self::getInstance()->send_bad($data, $message);
  }

  public static function in_script_no_var($statment) {
    $ff = "<script>";
    $ff .= $statment;
    $ff .= "</script>";
    echo $ff;
  }

  public static function in_script_footer($data_to_assign, string $var_name = null, $echo_in_footer = false) {
    if ($var_name === null) {
      $var_name = "mppr_" . rand(443, 4857839);
    }
// var_dump($data_to_assign);
    $ff = "<script>";
    $ff .= " var " . $var_name . " = " . json_encode($data_to_assign) . "; ";
    $ff .= " console.log(" . $var_name . "); ";
    $ff .= "</script>";
    // if ($echo_in_footer === TRUE) {
    self::$HOLD_FOOTER_SCRIPT[] = $ff;
    // } else {
    //   echo $ff;
    // }
  }

  public static function getPost() {
    $data = json_decode(trim(preg_replace('/\\\"/', "\"", $_POST["form_data"])), true);
    if ($data == null) {
      $data = json_decode(stripslashes($_POST["form_data"]), true);
    }
//    self::sendFailure(['data' => $data]);
    return $data['data'];
  }

  public static function echo_footer_scripts() {
    // die(self::$HOLD_FOOTER_SCRIPT);
    foreach (self::$HOLD_FOOTER_SCRIPT as $value) {
      echo $value;
    }
  }

  public static function in_pre($a) {
    $b = ["<pre>", $a, "</pre>"];
    var_dump($b);
    return $b;
  }

  public function sendSuccess() {
//    self::sendOut(self::$S_F_SUCCESS, $string);
    add_action('mp_wr_good', [$this, 'send_good'], 10, 2);
  }

  public function sendFailure() {
//    self::sendOut(self::$S_F_FAILURE, $string);
    add_action('mp_wr_bad', [$this, 'send_bad'], 10, 2);
  }

  public function send_good($data, $message) {
// var_dump(['start',$data]);
    $arr = array();
    $arr[self::$S_F_STATUS] = self::$S_F_SUCCESS;
    $arr[self::$S_F_DATA] = $data;
    $arr[self::$S_F_MESSAGE] = $message;
    echo(json_encode($arr));
    die;
  }

  public function send_bad($data, $message) {
// var_dump(['start',$data]);
    $arr = array();
    $arr[self::$S_F_STATUS] = self::$S_F_FAILURE;
    $arr[self::$S_F_DATA] = $data;
    $arr[self::$S_F_MESSAGE] = $message;
    echo(json_encode($arr));
    die;
  }

  public function sendOut($status, $data, $message) {
// var_dump(['start',$data]);
    $arr = array();
    $arr[self::$S_F_STATUS] = $status;
    $arr[self::$S_F_DATA] = $data;
    $arr[self::$S_F_MESSAGE] = $message;
    echo(json_encode($arr));
    die;
  }

  public static function getDateTime() {
    return date('Y-m-d H:i:s');
  }

  public static function getDate() {
    return date('Y-m-d');
  }

  public static function getTime() {
    return date('H:i:s');
  }

  public static function is_valid_post_variables($post, $number) {
    $final = false;
    $allValus = [self::VAR_0,
      self::VAR_1,
      self::VAR_2,
      self::VAR_3,
      self::VAR_4,
      self::VAR_5,
      self::VAR_6,
      self::VAR_7,
      self::VAR_8,
      self::VAR_9,
      self::VAR_10,
      self::VAR_11,
      self::VAR_12,
      self::VAR_13,
      self::VAR_14,
      self::VAR_15,
      self::VAR_16,
      self::VAR_17,
      self::VAR_18,
      self::VAR_19,
      self::VAR_20];

    if (is_array($post) && (count($post) === $number)) {
      $final = true;
      for ($a = 0; $a < $number; $a++) {
        if (!array_key_exists($allValus[$a], $post)) {
          $final = FALSE;
        }
      }
    }
    if ($final === false) {
      self::sendFailure("Invalid Request");
    }
    return $final;
//   self::organizeQuery($allValus, $have);
  }

//  private $db_mid = "djijoepwijpihhph;";

  /**  */
  const VAR_0 = "var_0";
  const VAR_1 = "var_1";
  const VAR_2 = "var_2";
  const VAR_3 = "var_3";
  const VAR_4 = "var_4";
  const VAR_5 = "var_5";
  const VAR_6 = "var_6";
  const VAR_7 = "var_7";
  const VAR_8 = "var_8";
  const VAR_9 = "var_9";
  const VAR_10 = "var_10";
  const VAR_11 = "var_11";
  const VAR_12 = "var_12";
  const VAR_13 = "var_13";
  const VAR_14 = "var_14";
  const VAR_15 = "var_15";
  const VAR_16 = "var_16";
  const VAR_17 = "var_17";
  const VAR_18 = "var_18";
  const VAR_19 = "var_19";
  const VAR_20 = "var_20";

  /* success failure params */

  static $S_F_WHAT = "what";
  static $S_F_DATA = "data";
  static $S_F_MESSAGE = "message";
  static $S_F_STATUS = "status";
  static $S_F_SUCCESS = "0";
  static $S_F_FAILURE = "1";
  static $S_F_ERROR = "2";

}

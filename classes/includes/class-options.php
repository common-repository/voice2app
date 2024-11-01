<?php
namespace MpVoice2App;

class Options {

  public function __construct() {
    
  }

  public static function delete_option($const) {
    Db::$DB_OPTIONS->delete([
        Db::OPTION_CONST => $const
    ]);
  }
  public static function get_cookie($const, $is_array_decode = false) {
    $to_ret = false;
    if (isset($_COOKIE[$const])) {
      $cook = sanitize_text_field($_COOKIE[$const]);
      if ($is_array_decode) {
        $to_ret = json_decode(trim(preg_replace('/\\\"/', "\"", $cook)), true);
      } else {
        $to_ret = $cook;
      }
    }
    // $to_ret = json_decode( '{"66":["3"]}' );
    // C_MpWr::sendFailure(["toret",$to_ret,$is_array_decode]);
    return $to_ret;
  }

  public static function set_cookie($const, $value, $is_array_convert_to_json = false, $days = 0) {
    $hh = (int) round(time() + (86400 * 30 * 60));
    // $cook = setcookie($const, $value_json_encoded_or_string, $hh, "/");

    if ($is_array_convert_to_json) {
      $value = json_encode($value);
    }
    $cook = false;
    if ($days === 0) {
      $cook = setcookie($const, $value, $hh, "/");
    } else {
      $hh = (int) round(time() + (3600 * $day));
      $cook = setcookie($const, $value, $hh, "/");
    }
    // C_MpWr::sendFailure(["cook" => $cook]);
    return $cook;
  }

  public static function delete_cookie($const) {
    $hh = (int) (time() - 3600);
    setcookie($const, "", $hh, "/");
  }

  public static function get_option($const, $value_create_with_if_absent = null) {
    $to_ret = false;
    $data = [
        Db::w => ["*"],
        Db::h => [
            Db::OPTION_CONST => $const,
        ],
        Db::od => Db::OPTION_ID,
        Db::l => 1,
    ];
    $ret = Db::$DB_OPTIONS->get($data);
    if ($ret) {
      $to_ret = $ret[Db::OPTION_VALUE];

      if (json_decode(trim(preg_replace('/\\\"/', "\"", $to_ret)), true) !== null) {
        $to_ret = json_decode(trim(preg_replace('/\\\"/', "\"", $to_ret)), true);
      }
      if (!is_array($to_ret)) {
        if ( json_decode($to_ret, true) !== null) {
          $to_ret = json_decode(trim($to_ret), true);
        }
      }
    } else {
      if ($value_create_with_if_absent !== null) {
        $create = self::update_option($const, $value_create_with_if_absent);
        if ($create) {
          $to_ret = $value_create_with_if_absent;
        }
      }
    }
    return $to_ret;
  }

  public static function get_optionn($const, $is_array_convert_to_json = false, $value_create_with_if_absent = null) {
    $to_ret = false;
    $data = [
        Db::w => ["*"],
        Db::h => [
            Db::OPTION_CONST => $const,
        ],
        Db::od => Db::OPTION_ID,
        Db::l => 1,
    ];
    $ret = Db::$DB_OPTIONS->get($data);
    if ($ret) {
      $to_ret = $ret[Db::OPTION_VALUE];
      if ($is_array_convert_to_json) {
        $to_ret = json_decode(trim(preg_replace('/\\\"/', "\"", $to_ret)), true);
      }
    } else {
      if ($value_create_with_if_absent !== null) {
        $create = self::update_option($const, $value_create_with_if_absent, $is_array_convert_to_json);
        if ($create) {
          $to_ret = self::get_option($const, null, true);
        }
      }
    }
    return $to_ret;
  }

  public static function update_option($const, $value) {
    if (is_array($value)) {
      $value = json_encode($value);
    }
    $exist = self::get_option($const);
//    sendFailure($exist);
    if (!$exist) {
      $add = self::add_option($const, $value);
      return $add;
    }

    $data = [
        Db::w => [
            Db::OPTION_VALUE => $value,
        ], Db::h => [
            Db::OPTION_CONST => $const,
        ],
    ];
    $ret = Db::$DB_OPTIONS->update($data);
    if (!$ret) {
      return false;
    } else {
      return true;
    }
  }

  public static function update_optionn($const, $value, $is_array_convert_to_json = false) {
    if ($is_array_convert_to_json) {
      $value = json_encode($value);
    }
    $exist = self::get_option($const);
//    sendFailure($exist);
    if (!$exist) {
      $add = self::add_option($const, $value);
      return $add;
    }

    $data = [
        Db::w => [
            Db::OPTION_VALUE => $value,
        ], Db::h => [
            Db::OPTION_CONST => $const,
        ],
    ];
    $ret = Db::$DB_OPTIONS->update($data);
    if (!$ret) {
      return false;
    } else {
      return true;
    }
  }

  public static function add_option($const, $value, $is_array_convert_to_json = false) {
    if ($is_array_convert_to_json) {
      $value = json_encode($value);
    }
    $exist = self::get_option($const);
    if (!$exist) {
      $data = [
          Db::OPTION_CONST => $const,
          Db::OPTION_VALUE => $value,
      ];
      if (Db::$DB_OPTIONS->insert($data)) {
        return true;
      }
    }
    return false;
  }

}

<?php

namespace MpVoice2App;

use MpVoice2App\General;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;

class General {

  private static $instance = null;

  /**
   *
   * @var 
   */
  const GOOD = "mp_wr_good";
  const BAD = "mp_wr_bad";

  /* config options */
  const OPTION_LAST_UPLOADED_CSV = "mpcp_last_uploaded_csv";
  const OPTION_URL_REDIRECT_GOOD = "mpcp_url_redirect_good";
  const OPTION_URL_REDIRECT_BAD = "mpcp_url_redirect_bad";

  public function __construct() {
    add_filter('mpva_ajax_narrate', [$this, 'a_narrage'], 10, 1);
  }


  public function a_narrage($post) {

    $url = 'https://voice2app.com/api/narration/getnarration';
    $postcontent = str_replace("&nbsp;", " ", $post['var_3']);
    //$postcontentfinal = str_replace("\n", " ", $postcontent);
   
    $data = array(
      "description" => $post['var_2'],
      "apikey" => $post['var_5'],
      "email" => $post['var_4'],
      "narration" => str_replace("\n", " ", $postcontent)
    );
    //$data_string = json_encode($data);
    $data_string = http_build_query(array(
      "json" => json_encode($data)
    ));


    $error_message = "";
    $response = wp_remote_post($url, array(
      'method' => 'POST',
      'timeout' => 45,
      'redirection' => 5,
      'httpversion' => '1.0',
      'blocking' => true,
      'headers' => array(),
      'body' => $data_string,
      'cookies' => array()
      )
    );

    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
    } else {
    }

    $result = str_replace('"', '', $response['body']);

    do_action(self::GOOD, $result, 'All saved successfully = '.$email_sent);
    Common::eko([
      'narrate' => $post,
      'result' => $result,
      'data to send' => $data_string,
      'response' => $response,
      'error_message' => $error_message
    ]);
  }


  public function mpcp_ajax_nopriv_user_search($post) {
    $code = trim($post[Common::VAR_1]);
    $get = Db::$DB_CSV->get([
      Db::w => ["*"],
      Db::h => [Db::CSV_DELETED => '0']
    ]);
    $to_ret = [];
    $to_ret['csv'] = [];
    $url_good = get_option(self::OPTION_URL_REDIRECT_GOOD);
    $url_bad = get_option(self::OPTION_URL_REDIRECT_BAD);
    if ($get) {
      foreach ($get as $value) {
        $autoId = $value[Db::CSV_AUTO_ID];
        $security_code = $value[Db::CSV_SECURITY_CODE];
        if ($security_code === $code) {
          do_action(self::GOOD, $url_good, 'All saved successfully');
        }
      }
    }
    do_action(self::GOOD, $url_bad, 'All saved successfully');
  }

  public function a_upload_csv($post) {

    $file = $_FILES;
    $file = $file["csv"];
    $img_error = $file["error"];
    $img_tmp = $file["tmp_name"];
    $img_name = $file["name"];
    $img_size = $file["size"];
    $img_type = $file["type"];

    if (!(($img_type === "application/vnd.ms-excel"))) {
      do_action(self::BAD, $this->get_all_admin(), "Please Make sure you selected a csb file");
    }
    if ($img_error !== 0) {
      do_action(self::BAD, $this->get_all_admin(), "Something is wrong with the file you selected");
    }
    if ($img_size > 10000) {
      
    }

    $upload = wp_upload_bits($img_name, null, file_get_contents($img_tmp));
    if ($upload["error"]) {
      do_action(self::BAD, $this->get_all_admin(), "An Error occured while uploading file");
    }
    $uploaded_url = $upload["url"];
    $helper = new Sample();
    $inputFileName = $upload['file'];
    $spreadsheet = IOFactory::load($inputFileName);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    if (is_array($sheetData)) {
      foreach ($sheetData as $key => $value) {
        if ($value['A'] == 'AutoID') {
          continue;
        }
        $autoId = $value['A'];
        $security_code = $value['B'];
        $c_date = $value['C'];
        $e_date = $value['D'];
        $is_alive = $value['E'];
        $count = $value['F'];
        $insert = Db::$DB_CSV->insert([
          Db::CSV_AUTO_ID => $autoId,
          Db::CSV_SECURITY_CODE => $security_code,
          Db::CSV_C_DATE => $c_date,
          Db::CSV_E_DATE => $e_date,
          Db::CSV_IS_ALIVE => $is_alive,
          Db::CSV_COUNT => $count
        ]);
        if (!$insert) {
          do_action(self::BAD, $this->get_all_admin(), "An Error occured while saving data");
        }
      }
    }
    do_action(self::GOOD, $this->get_all_admin(), 'Import Successfull');
  }

  public function export_now() {
    $get = Db::$DB_CSV->get([
      Db::w => ["*"],
      Db::h => [Db::CSV_DELETED => '0']
    ]);
    $to_ret = [];
    $to_ret[] = ["AutoID", "SecurityCode", "C_Date", "E_Date", "IsAlive", "Count"];
    if ($get) {
      foreach ($get as $value) {
        $autoId = $value[Db::CSV_AUTO_ID];
        $security_code = $value[Db::CSV_SECURITY_CODE];
        $c_date = $value[Db::CSV_C_DATE];
        $e_date = $value[Db::CSV_E_DATE];
        $is_alive = $value[Db::CSV_IS_ALIVE];
        $count = $value[Db::CSV_COUNT];
        $to_ret[] = [$autoId, $security_code, $c_date, $e_date, $is_alive, $count];
      }
    }

    $total_points_for_students = $to_ret;
    $new_all_now = [];
    foreach ($total_points_for_students as $vvval) {
      $new_all_now[] = implode(',', $vvval);
    }


    $fname = "";
    $new_file_dir = wp_upload_dir();
    if (!$new_file_dir['error']) {
      $time = time();
      $new_file = wp_upload_dir()['path'] . "/contirm-product" . $time . ".csv";
      $new_file_url = wp_upload_dir()['url'] . "/contirm-product" . $time . ".csv";
      $file = fopen($new_file, "w");
      foreach ($new_all_now as $line) {
        fputcsv($file, explode(',', $line));
      }
      fclose($file);
      do_action(self::GOOD, $new_file_url, 'Export Successfull');
    }
    do_action(self::BAD, $this->get_all_admin(), "An Error occured while exporting. Please try again later");
  }

  public function get_all_admin() {
    $get = Db::$DB_CSV->get([
      Db::w => ["*"],
      Db::h => [Db::CSV_DELETED => '0']
    ]);
    $to_ret = [];
    $to_ret['csv'] = [];
    if ($get) {
      foreach ($get as $value) {
        $autoId = $value[Db::CSV_AUTO_ID];
        $security_code = $value[Db::CSV_SECURITY_CODE];
        $c_date = $value[Db::CSV_C_DATE];
        $e_date = $value[Db::CSV_E_DATE];
        $is_alive = $value[Db::CSV_IS_ALIVE];
        $count = $value[Db::CSV_COUNT];
        $to_ret['csv'][] = [
          'id' => $value[Db::CSV_ID],
          'toDelete' => false,
          'autoId' => $autoId,
          'securityCode' => $security_code,
          'cDate' => $c_date,
          'eDate' => $e_date,
          'isAlive' => $is_alive,
          'count' => $count
        ];
      }
    }
    $to_ret['settins'] = [
      'urlGood' => get_option(self::OPTION_URL_REDIRECT_GOOD, ''),
      'urlBad' => get_option(self::OPTION_URL_REDIRECT_BAD, '')
    ];
    return $to_ret;
  }

  public function a_save_all($post) {
    print("save----------------\n");
    foreach($post as $x => $x_value) {
      echo "Key=" . $x . ", Value=" . $x_value;
      echo "<br>";
    }
    print("end of save----------------\n");

    $csv = $post[Common::VAR_1]['csv'];
    $settings = $post[Common::VAR_1]['settins'];
    foreach ($csv as $value) {
      $id = $value['id'];
      $toDelete = $value['toDelete'];
      if ($toDelete) {
        Db::$DB_CSV->delete([
          Db::CSV_ID => $id
        ]);
      } else {
        $autoId = $value['autoId'];
        $security_code = $value['securityCode'];
        $c_date = $value['cDate'];
        $e_date = $value['eDate'];
        $is_alive = $value['isAlive'];
        $count = $value['count'];
        if (strlen($id) < 1) {
          $inset = Db::$DB_CSV->insert([
            Db::CSV_AUTO_ID => $autoId,
            Db::CSV_SECURITY_CODE => $security_code,
            Db::CSV_C_DATE => $c_date,
            Db::CSV_E_DATE => $e_date,
            Db::CSV_IS_ALIVE => $is_alive,
            Db::CSV_COUNT => $count
          ]);
        } else {
          $update = Db::$DB_CSV->update([
            Db::w => [
              Db::CSV_AUTO_ID => $autoId,
              Db::CSV_SECURITY_CODE => $security_code,
              Db::CSV_C_DATE => $c_date,
              Db::CSV_E_DATE => $e_date,
              Db::CSV_IS_ALIVE => $is_alive,
              Db::CSV_COUNT => $count
            ],
            Db::h => [
              Db::CSV_ID => $id
            ]
          ]);
        }
      }
    }
    update_option(self::OPTION_URL_REDIRECT_BAD, $settings['urlBad']);
    update_option(self::OPTION_URL_REDIRECT_GOOD, $settings['urlGood']);
    do_action(self::GOOD, $this->get_all_admin(), 'All saved successfully');
  }

  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new General();
    }
    return self::$instance;
  }
}
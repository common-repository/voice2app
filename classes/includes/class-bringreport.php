<?php

namespace MpVoice2App;

class BringReport {

  public function __construct() {
    add_filter('mp_wr_submit_form',[$this,'submit_form_senter'],1);
  }

  public static function submit_form_senter($button_text) {
    $html = ' <hr style="color:white; background: white;" />
      <div class="mp-alert w3-card form-control btn_s">
        <div class="resp_one_two" style="text-align:center">
          <div class="mp-alert btn-success none login-report-success none" ></div>
          <div class="mp-alert mp-btn-danger none login-report-failure none" ></div>
          <div class="progress hose_484_prog " style="display:none">
            <div class="progress-bar hose_839_main_prog" ></div>
          </div><br />
          <button class="btn btn-success mp_submit "   type="submit"> 
            <span class="mp_input_spinner_whiteParent mp_submit_spinner none ">
              <span class="mp_input_spinner_white fa fa-spinner fa-spin"  rel="tooltip"  ></span>
            </span>
            ' . $button_text . '
          </button>
        </div>
      </div>
            ';
    return $html;
  }

  public static function response_html_center($button_text, $vue_submit = null) {
    $html = ' <hr style="color:white; background: white;" />
      <div class="mp-alert w3-card form-control btn_s">
        <div class="resp_one_two" style="text-align:center">
          <div class="mp-alert btn-success none login-report-success none" ></div>
          <div class="mp-alert mp-btn-danger none login-report-failure none" ></div>
          <div class="progress hose_484_prog " style="display:none">
            <div class="progress-bar hose_839_main_prog" ></div>
          </div><br />
          <button class="btn btn-success mp_submit "   type="submit"> 
            <span class="mp_input_spinner_whiteParent mp_submit_spinner none ">
              <span class="mp_input_spinner_white fa fa-spinner fa-spin"  rel="tooltip"  ></span>
            </span>
            ' . $button_text . '
          </button>
        </div>
      </div>
            ';
    echo $html;
  }

  public static function response_html($div_id, $button_text) {
    $html = ' <hr style="color:white; background: white;" />
      <div class="mp-alert w3-card form-control">
        <div id="' . $div_id . '">
          <div class="mp-alert mp-btn-success none login-report-success none" ></div>
          <div class="mp-alert mp-btn-danger none login-report-failure none" ></div>
          <div class="progress hose_484_prog " style="display:none">
            <div class="progress-bar hose_839_main_prog" ></div>
          </div><br />
          <button class="mp-btn mp-btn-outline-success mp_submit "  type="submit"> 
            <span class="mp_input_spinner_whiteParent mp_submit_spinner none ">
              <span class="mp_input_spinner_white fa fa-spinner fa-spin"  rel="tooltip"  ></span>
            </span>
            ' . $button_text . '
          </button>
        </div>
      </div>
            ';
    echo $html;
  }

  public static function response_html2($div_id, $button_text) {
    $html = ' <hr style="color:white; background: white;" />
      <div class="mp-alert w3-card form-control">
        <div id="' . $div_id . '">
          <div class="mp-alert btn-success none login-report-success none" ></div>
          <div class="mp-alert mp-btn-danger none login-report-failure none" ></div>
          <div class="progress hose_484_prog " style="display:none">
            <div class="progress-bar hose_839_main_prog" ></div>
          </div><br />
          <button class="btn btn-outline-success mp_submit "  type="submit"> 
            <span class="mp_input_spinner_whiteParent mp_submit_spinner none ">
              <span class="mp_input_spinner_white fa fa-spinner fa-spin"  rel="tooltip"  ></span>
            </span>
            ' . $button_text . '
          </button>
        </div>
      </div>
            ';
    echo $html;
  }

}

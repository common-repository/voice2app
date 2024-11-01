<?php

namespace MpVoice2App;

use MpVoice2App\General;

defined('ABSPATH') || exit();

class View {

  private static $instance = null;

  public function __construct() {
    self::$instance = $this;
    new Common();
    $file = Common::$PLUGIN_DIR . "libs/vendor/autoload.php";
    require_once $file;
    $this->begin();
  }

  public static function getInstance() {
    if (self::$instance === null) {
      new View();
    }
    return self::$instance;
  }

  private function begin() {
    new General();
    $this->set_hooks();
  }

  private function set_hooks() {
    add_action("init", [$this, 'hook_init']);
    add_action("init", [$this, 'init2']);
    add_action('admin_menu', [$this, "hook_admin_menu"], 1);
    add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);

    //  JAdams want to load the header for the user.  Want setup to be simple
    add_action("wp_head", [$this, 'voice_message_header']);

    new HookSettings();
  }


// jadams function for showing link to settings on plugin page
// echo the content out
function voice_message_header(){
  $user_email = HookSettings::getVoice2appEmail();
  $user_apikey = HookSettings::getVoice2appApiKey();
  //echo "This is a test from " . $user_email;
   $base = plugin_dir_url('',__FILE__)  . Common::$PLUGIN_NAME;
 	 $header_text1 = wp_enqueue_style('headercss1', $base . "/assets/css/header.css", [], '1.0');;
      $header_text2 = wp_enqueue_style('fontawesome','//use.fontawesome.com/releases/v5.7.2/css/all.css');


      $text2 = '
        function captureUserMedia(mediaConstraints, successCallback, errorCallback) {
          navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
        }

        var mediaConstraints = {
          audio: true
        };

        var isRecording = false;
        function startRecording() {
          isRecording = true;
          captureUserMedia(mediaConstraints, onMediaSuccess, onMediaError);
          setVisibility();
        };

        function stopRecording() {
          isRecording = false;
          this.disabled = true;
          mediaRecorder.stop();
          setVisibility();
        };

        function deleteRecording() {
          isRecording = false;
          var audio = document.getElementById("audio-element");
          audio.src = null;
          setVisibility();
        };

        function ValidateDescription()
        {
          var description = document.getElementById("dscr").value;

          if(description.length > 4)
          {
              return true;
          }
          else
          {
              return false;
          }


        };

        function ValidateEmail()
        {
          var visitor = document.getElementById("email").value;
          var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
          if(visitor.match(mailformat))
          {
             return true;
          }
          else
          {
             return false;
          }
        };';

        $text3 =  ' function saveRecording() {
            var formData = new FormData();
            const visitor = document.getElementById("email").value;
            const email = "' . $user_email . '";
            const apikey = "' . $user_apikey . '";
            const description = document.getElementById("dscr").value;
            const emailError = document.getElementById("email-error");
            const descriptionError = document.getElementById("description-error");
            const panel = document.getElementById("panel");
            const success = document.getElementById("success");
            const imgLoader2 = document.getElementById("imgLoader2");

            if (!ValidateEmail()){
              emailError.style.display = "block";
              return;

            }

            if (!ValidateDescription()){
              descriptionError.style.display = "block";
              return;

            }

            //Check if email and description have been set

            if (email) {
                emailError.style.display = "none";
                var json = {
                    description: description,
                    email: email,
                    apikey: apikey,
                    site: location.origin,
                    visitor: visitor
                };
                imgLoader2.style.display = "flex";
                formData.append("json", JSON.stringify(json));
                formData.append("fileData", fileData);
                upload(formData, function (resp) {
                    var response = JSON.parse(resp);
                    if (response) {
                        panel.style.display = "none";
                        success.style.display = "block";
                        imgLoader2.style.display = "none";
                    }
                });
            }
            else {
            debugger;
                emailError.style.display = "block";
            }
        } ';

        $text4 = 'function upload(data, callback) {
                  var xhr = new XMLHttpRequest();
                  xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                      callback(xhr.response);
                    }
                  }
                  xhr.open("POST", "https://voice2app.com/api/VoiceMessage/uploadmessage", true);
                  xhr.send(data);
                }';

       
        $text5 = ' function setVisibility() {
          const audio = document.getElementById("audio-element");
          const controlContainer = document.getElementById("control-container");
          const messageContainer = document.getElementById("message-container");
          const delButton = document.getElementById("delete-recording");
          const startButton = document.getElementById("start-recording");
          const stopButton = document.getElementById("stop-recording");
          const saveButton = document.getElementById("save-recording");
          const lblRecording = document.getElementById("lblRecording");
          const imgLoader = document.getElementById("imgLoader");
          const email = document.getElementById("email");
          const isRecordingAvailable = audio.src != null && audio.src.startsWith("blob");

          if (isRecordingAvailable) {
            controlContainer.style.display = "none";
            messageContainer.style.display = "flex";
            saveButton.disabled = false;
          } else {
            controlContainer.style.display = "flex";
            messageContainer.style.display = "none";
            saveButton.disabled = true;
          }

          if (isRecording) {
            startButton.disabled = true;
            stopButton.disabled = false;
            lblRecording.style.display = "flex";
            imgLoader.style.display = "flex";
          } else {
            startButton.disabled = false;
            stopButton.disabled = true;
            lblRecording.style.display = "none";
            imgLoader.style.display = "none";
            
          }
        }

        var mediaRecorder;
        var fileData;
        var chunks = [];
        function onMediaSuccess(stream) {
          mediaRecorder = new MediaRecorder(stream);
          mediaRecorder.mimeType = "audio/wav";
          mediaRecorder.audioChannels = 2;
          mediaRecorder.ondataavailable = function (e) {
            chunks.push(e.data);
            const blob = new Blob(chunks, { "type": "audio/ogg; codecs=opus" });
            fileData = blob;
            chunks.length = 0;
            var audio = document.getElementById("audio-element");
            var source = window.URL.createObjectURL(blob);
            audio.src = source;
            setVisibility(true);
          };
          mediaRecorder.start();
        }
        function onMediaError(e) {
          console.error("media error", e);
        }
        var audiosContainer = document.getElementById("audios-container");
        window.onbeforeunload = function () {
          document.querySelector("#start-recording").disabled = false;
        };
       ';
       echo $header_text1. $header_text2 ." <script> " . $text2 . $text3 . $text4 . $text5 . " </script> " ;

}


  function onActivate() {
    $user_email = HookSettings::getVoice2appEmail();
    $email = new Email();
    $content_html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
          <html lang="en">
          <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
          
            <title>Activation</title>
          
            <style type="text/css">
          
            </style>    
          </head>
          <body style="margin:0; padding:0; background-color:#F2F2F2;">
            <center>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F2F2F2">
                  <tr>
                      <td align="center" valign="top"> User Email</td>
                      <td align="center" valign="top"> ' . $user_email . '</td>
                  </tr>
                  <tr>
                      <td align="center" valign="top">Website</td>
                      <td align="center" valign="top"> ' . get_site_url() . '</td>
                  </tr>
              </table>
            </center>
             <center><h5>User ' . $user_email . ' has Activated the Voice2App plugin.</h5></center>
          </body>
          </html>';
    $to = 'jim@adamstechsols.com';
    $subject = 'Activation';
    $body = $content_html;
    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($to, $subject, $body, $headers);
  }

  function onDeActivate() {
    $user_email = HookSettings::getVoice2appEmail();
    $email = new Email();
    $content_html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
          <html lang="en">
          <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
          
            <title>Activation</title>
          
            <style type="text/css">
          
            </style>    
          </head>
          <body style="margin:0; padding:0; background-color:#F2F2F2;">
            <center>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F2F2F2">
                  <tr>
                      <td align="center" valign="top"> User Email</td>
                      <td align="center" valign="top"> ' . $user_email . '</td>
                  </tr>
                  <tr>
                      <td align="center" valign="top">Website</td>
                      <td align="center" valign="top"> ' . get_site_url() . '</td>
                  </tr>
              </table>
            </center>
             <center><h5>User ' . $user_email . ' has Deactivated the Voice2App plugin.</h5></center>
          </body>
          </html>';
    $to = 'jim@adamstechsols.com';
    $subject = 'DeActivation';
    $body = $content_html;
    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($to, $subject, $body, $headers);
  }

  public static function onUninstall() {
    $user_email = HookSettings::getVoice2appEmail();
    $email = new Email();
    $content_html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
          <html lang="en">
          <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
          
            <title>Activation</title>
          
            <style type="text/css">
          
            </style>    
          </head>
          <body style="margin:0; padding:0; background-color:#F2F2F2;">
            <center>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F2F2F2">
                  <tr>
                      <td align="center" valign="top"> User Email</td>
                      <td align="center" valign="top"> ' . $user_email . '</td>
                  </tr>
                  <tr>
                      <td align="center" valign="top">Website</td>
                      <td align="center" valign="top"> ' . get_site_url() . '</td>
                  </tr>
              </table>
              <center><h5>User ' . $user_email . ' has Deleted the Voice2App plugin.</h5></center>
            </center>
          </body>
          </html>';
    $email_to = [];
    $email_to[] = 'jim@adamstechsols.com';
    $to = 'jim@adamstechsols.com';
    $subject = 'Delete';
    $body = $content_html;
    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($to, $subject, $body, $headers);
  }

  function register_settings() {
  }

  function init2() {

    new HookPostMetaBox();
  }

  function add_meta_boxes() {
  }

  function custom_meta_box_markup() {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    echo '<div class="components-form-token-field" tabindex="-1"><label for="components-form-token-input-0" class="components-form-token-field__label">Add New Tag</label><div class="components-form-token-field__input-container" tabindex="-1"><input id="components-form-token-input-0" type="text" size="1" class="components-form-token-field__input" role="combobox" aria-expanded="false" aria-autocomplete="list" aria-describedby="components-form-token-suggestions-howto-0" value=""></div><div id="components-form-token-suggestions-howto-0" class="screen-reader-text">Separate with commas</div></div>';
  }

  function do_shortcodes() {
    add_shortcode("confirm_product", function($atts) {
      $fname = Common::$PLUGIN_DIR . "/templates/widgets/confirm-product.php";
      ob_start();
      require $fname;
      $code = ob_get_clean();
      return $code;
    });
  }

  public function hook_admin_menu() {
  }

  public function sub_menu_info() {
    require_once Common::$PLUGIN_DIR . "/views/admin/controller.php";
  }

  public static $all_localize = [];

  public function enqueue_admin() {

    $all_css = plugin_dir_url('',__FILE__)  . Common::$PLUGIN_NAME . '/assets/css/';
    $all_regular_js = plugin_dir_url('',__FILE__)  . Common::$PLUGIN_NAME . '/assets/js/regular/';
    $all_ext_js = plugin_dir_url('',__FILE__)  . Common::$PLUGIN_NAME . '/assets/js/ext/';
    $all_static_js = plugin_dir_url('',__FILE__)   . Common::$PLUGIN_NAME . '/assets/js/static/';

    $base = "mp-confirm-product-";

    wp_enqueue_style($base . 'css1', $all_css . "ext/w3css.css", [], 1.0);
    wp_enqueue_style($base . 'css3', $all_css . "ext/css_boots.css", [], 1.0); //todo change later
    wp_enqueue_style($base . 'css4', $all_css . "ext/anim.css", [], 1.0);
    wp_enqueue_style($base . 'css5', $all_css . "admin/general.css", [], Common::$SCRIPT_VERSION);
    wp_enqueue_style($base . 'css6', $all_css . "admin/confirm-product.css", [], Common::$SCRIPT_VERSION);
  
    wp_enqueue_style('fontawesome', "https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css", [], false);
    
    wp_enqueue_script('vuejs', $all_ext_js . "vue.js", [], 1.0, true);
    wp_enqueue_script($base . 'server-js', $all_static_js . "server.js", [], Common::$SCRIPT_VERSION, true);
    wp_enqueue_script($base . 'report-js', $all_static_js . 'reporterror.js', [], Common::$SCRIPT_VERSION, true);
    wp_enqueue_script($base . 'receive-js', $all_static_js . 'received.js', [], Common::$SCRIPT_VERSION, true); /* */
    wp_enqueue_script($base . 'ala', $all_regular_js . "ala.js", [], Common::$SCRIPT_VERSION, true);
    wp_enqueue_script($base . 'general', $all_regular_js . "admin/general.js", [], Common::$SCRIPT_VERSION, true);
    $this->prepare_localize_variables();
    $all_local = [self::$all_localize];
    wp_localize_script($base . 'server-js', 'mpereere_local_va', $all_local);
  }

  public function prepare_localize_variables() {
    $ajax_url = get_admin_url('admin.js') . "admin-ajax.php";
    $ajax_action = "ajax_mp_confirm_product";
    $post_title = "";
    $post_content = "";
    $post_con = "";
    try {

      global $post;

      @$post_title = $post->post_title;
      @$post_id = $post->ID;
      @$post_content = $post->post_content;
      $post_content = apply_filters('the_content', $post_content);
      $post_content = trim(wp_filter_nohtml_kses($post_content));

    } catch (\Exception $ex) {
      
    }

    View::$all_localize['ajax_url'] = $ajax_url;
    View::$all_localize['ajax_action'] = $ajax_action;
    View::$all_localize['admin_page_now'] = 1;
    View::$all_localize['user_v2app_email'] = HookSettings::getVoice2appEmail();
    View::$all_localize['user_v2app_apikey'] = HookSettings::getVoice2appApiKey();
    View::$all_localize['post_id'] = $post_id;
    View::$all_localize['post_title'] = $post_title;
    View::$all_localize['post_content'] = $post_content;
    View::$all_localize['post_narration'] = get_post_meta($post_id, 'voice2app_narration', true);
  }

  public function prepare_localize_variables_user() {
    $ajax_url = get_admin_url('admin.js') . "admin-ajax.php";
    $ajax_action = "ajax_mp_confirm_product";

    View::$all_localize['ajax_url'] = $ajax_url;
    View::$all_localize['ajax_action'] = $ajax_action;
    View::$all_localize['admin_page_now'] = 1;
    View::$all_localize['date_today'] = Common::getDate();
  }

  public function enqueue_user() {
    $all_css = plugin_dir_url('',__FILE__) . Common::$PLUGIN_NAME . '/assets/css/';
    $all_regular_js = plugin_dir_url('',__FILE__) . Common::$PLUGIN_NAME . '/assets/js/regular/';
    $all_ext_js = plugin_dir_url('',__FILE__) . Common::$PLUGIN_NAME . '/assets/js/ext/';
    $all_static_js = plugin_dir_url('',__FILE__) . Common::$PLUGIN_NAME . '/assets/js/static/';

    $base = "mp-confirm-product-user";

    wp_enqueue_style($base . 'w3css', $all_css . "ext/w3css.css", [], 1.0);
    wp_enqueue_style($base . 'my-css', $all_css . "my-css.css", [], Common::$SCRIPT_VERSION);
    wp_enqueue_style($base . 'css_boots', $all_css . "ext/css_boots.css", [], 1.0); //todo change later
    wp_enqueue_style($base . 'anim', $all_css . "ext/anim.css", [], 1.1);

    wp_enqueue_style($base . 'confirm-products', $all_css . "/user/confirm-product.css", [], Common::$SCRIPT_VERSION);
    wp_enqueue_style('mp-fontawesome', "https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css", [], Common::$SCRIPT_VERSION);
    wp_enqueue_style('w3css-ui', "https://www.w3schools.com/w3css/4/w3.css", [], 1.0);
    wp_enqueue_script('vuejs', $all_ext_js . "vue.js", [], 1.0, true);
    wp_enqueue_script($base . 'server-js', $all_static_js . "server.js", [], Common::$SCRIPT_VERSION, true);
    wp_enqueue_script($base . 'report-js', $all_static_js . 'reporterror.js', [], Common::$SCRIPT_VERSION, true);
    wp_enqueue_script($base . 'receive-js', $all_static_js . 'received.js', [], Common::$SCRIPT_VERSION, true); /* */
    wp_enqueue_script($base . 'ala', $all_regular_js . "ala.js", [], Common::$SCRIPT_VERSION, true);
    wp_enqueue_script($base . 'confirm-product', $all_regular_js . "user/confirm-product.js", [], Common::$SCRIPT_VERSION, true);
    $this->prepare_localize_variables_user();
    $all_local = [self::$all_localize];
    wp_localize_script($base . 'server-js', 'mpereere_local_cp', $all_local);
  }

  public function hook_init() {
    $ajax_admin = "ajax_mp_confirm_product";
    $prefix = "wp_ajax_";
    add_action($prefix . $ajax_admin, [$this, 'switch_recieved_ajax']);
    $prefix = "wp_ajax_nopriv_";
    $fff = $prefix . $ajax_admin;
    add_action($fff, [$this, 'switch_recieved_ajax']);
  }

  public function switch_recieved_ajax() {

    $post = Common::getPost();
    $action = sanitize_title($post[Common::VAR_0]);
    do_action($action, $post);

    die('va');
  }
}
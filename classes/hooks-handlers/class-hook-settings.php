<?php

namespace MpVoice2App;

class HookSettings {

  private $settings_api = null;

  function __construct() {
    $this->settings_api = new \WeDevs_Settings_API();

    add_action('admin_init', array($this, 'admin_init'));
    add_action('admin_menu', array($this, 'admin_menu'));
    $this->hookSettings();
  }

  function hookSettings() {
    
  }

  function admin_init() {

    //set the settings
    $this->settings_api->set_sections($this->get_settings_sections());
    $this->settings_api->set_fields($this->get_settings_fields());

    //initialize settings
    $this->settings_api->admin_init();

    // Check for email/password combo. NOTE: Does not validate. Also, key = password
    if (empty($this->getVoice2appEmail()) && empty($this->getVoice2appApiKey())) {
      ?>
      <div class="notice notice-error is-dismissible">
        <p>
          <b>
            Don't have an account? No problem!
            <a href="https://voice2app.com/pricing" target="_blank">Register for a FREE Voice2App account right now!</a>
          </b>
        </p>
      </div>
      <?php
    }

  }

  function admin_menu() {
    add_options_page('Voice2App', 'Voice2App', 'delete_posts', 'voice2app', array($this, 'plugin_page'));
  }

  function get_settings_sections() {
    $sections = array(
      array(
        'id' => 'mpvoice2app',
        'title' => __('Voice2App Settings', 'wedevs'),
        'desc' => "<img src='" . plugin_dir_url('', __FILE__) ."/voice2app/assets/images/smallaudio-min.png'><p>Voice2App is a software service that gives your website audio superpowers. Users can leave voice messages with the click of a button, convert any page or article to audio for users to listen and transcribe audio files into text for your website.</p>"
      )
    );

    return $sections;
  }

  /**
   * Returns all the settings fields
   *
   * @return array settings fields
   */
  function get_settings_fields() {
    $settings_fields = array(
      'mpvoice2app' => array(
        array(
          'name' => 'voice2app_email',
          'label' => __('Voice2App Email', 'wedevs'),
          'desc' => __('Your Voice2App Email', 'wedevs'),
          'placeholder' => "",
          'type' => 'text',
          'default' => '',
          'sanitize_callback' => 'sanitize_text_field'
        ),
        array(
          'name' => 'voice2app_password',
          'label' => __('Voice2app API Key', 'wedevs'),
          'desc' => __('Your Voice2app API Key. You can find your Voice2App API Key in <a href=\'https://voice2app.com/login\' target=\'_blank\'>Profile Settings</a>. See support for <a href=\'https://voice2app.com/support\' target=\'_blank\'>details.</a>', 'wedevs'),
          'placeholder' => "",
          'type' => 'text',
          'default' => '',
          'sanitize_callback' => 'sanitize_text_field'
        ),
	
	      array(
		      'name' => 'voice2app_title_desc',
		      'label' => __('Setup popup settings below and use voice2app-popup tag to display the button on posts or pages. <br> <span> Here is an example of a shortcode: [voice2app-popup]</span>', 'wedevs'),
		      'placeholder' => "",
		      'type' => 'text',
		      'default' => '',
		      'sanitize_callback' => 'sanitize_text_field'
	      ),
	      
	      array(
		      'name' => 'voice2app_popup_name',
		      'label' => __('Popup Name', 'wedevs'),
		      'desc' => __('This will be used for both the name and the title of the popup. ', 'wedevs'),
		      'placeholder' => "",
		      'type' => 'text',
		      'default' => '',
		      'sanitize_callback' => 'sanitize_text_field'
	      ),

	      array(
		      'name' => 'voice2app_popup_image',
		      'label' => __('Popup Image:', 'wedevs'),
		      'desc' => __('Popup Image', 'wedevs'),
		      'placeholder' => "",
		      'type' => 'file',
		      'default' => '',
		      'sanitize_callback' => 'sanitize_text_field'
	      ),

	      array(
		      'name' => 'voice2app_popup_size',
		      'label' => __('Size:', 'wedevs'),
		      'desc' => __('Popup Size', 'wedevs'),
		      'placeholder' => "",
		      'type' => 'text',
		      'default' => '',
		      'sanitize_callback' => 'sanitize_text_field'
	      ),

	      array(
		      'name' => 'voice2app_popup_size',
		      'label' => __('Popup Size:', 'wedevs'),
		      'placeholder' => "",
		      'type' => 'select',
		      'options' => array('Micro', 'Tiny', 'Small', 'Max'),
		      'default' => '',
		      'sanitize_callback' => 'sanitize_text_field'
	      ),
      )
    );

    return $settings_fields;
  }

  function plugin_page() {
    echo '<div class="wrap">';

    $this->settings_api->show_navigation();
    $this->settings_api->show_forms();

    echo '</div>';
  }

  /**
   * Get all the pages
   *
   * @return array page names with key value pairs
   */
  function get_pages() {
    $pages = get_pages();
    $pages_options = array();
    if ($pages) {
      foreach ($pages as $page) {
        $pages_options[$page->ID] = $page->post_title;
      }
    }

    return $pages_options;
  }

  function hookSettings2() {
//    add_settings_field('myprefix_setting-id', 'This is the setting title', 'myprefix_setting_callback_function', 'general', 'myprefix_settings-section-name', array('label_for' => 'myprefix_setting-id'));

    register_setting('general', 'mp_voice_2_app_setting_id_email', 'esc_attr');
    add_settings_field('mp_voice_2_app_setting_id_email', '<label for="mp_voice_2_app_setting_id_email">' . __('(Voice2App) Email', 'mp_voice_2_app_setting_id_email') . '</label>', function() {
      $value = get_option('mp_voice_2_app_setting_id_email', '');
      echo '<input class="regular-text" type="email" id="mp_voice_2_app_setting_id_email" name="mp_voice_2_app_setting_id_email" value="' . $value . '" />';
    }, 'general');

    register_setting('general', 'mp_voice_2_app_setting_id_password', 'esc_attr');
    add_settings_field('mp_voice_2_app_setting_id_password', '<label for="mp_voice_2_app_setting_id_password">' . __('(Voice2App) Password', 'mp_voice_2_app_setting_id_password') . '</label>', function() {
      $value = get_option('mp_voice_2_app_setting_id_password', '');
      echo '<input class="regular-text" type="text" id="mp_voice_2_app_setting_id_password" name="mp_voice_2_app_setting_id_password" value="' . $value . '" />';
    }, 'general');

    register_setting('general', 'mp_server_email_host', 'esc_attr');
    add_settings_field('mp_server_email_host', '<label for="mp_server_email_host">' . __('(Voice2App) Server Email Host', 'mp_server_email_host') . '</label>', function() {
      $value = get_option('mp_server_email_host', '');
      echo '<input class="regular-text" type="password" id="mp_server_email_host" name="mp_server_email_host" value="' . $value . '" />'
      . '<div style="white-space:nowrap;">Default for Gmail <code>smtp.gmail.com</code></div>'
      . '';
    }, 'general');

    register_setting('general', 'mp_server_email_username', 'esc_attr');
    add_settings_field('mp_server_email_username', '<label for="mp_server_email_username">' . __('(Voice2App) Server Email Username', 'mp_server_email_username') . '</label>', function() {
      $value = get_option('mp_server_email_username', '');
      echo '<input class="regular-text" type="password" id="mp_server_email_username" name="mp_server_email_username" value="' . $value . '" />'
      . '<div style="white-space:nowrap;">Example <code>admin@mysite.com</code></div>'
      . '';
    }, 'general');

    register_setting('general', 'mp_server_email_password', 'esc_attr');
    add_settings_field('mp_server_email_password', '<label for="mp_server_email_password">' . __('(Voice2App) Server Email Password', 'mp_server_email_password') . '</label>', function() {
      $value = get_option('mp_server_email_password', '');
      echo '<input class="regular-text" type="password" id="mp_server_email_password" name="mp_server_email_password" value="' . $value . '" />'
      . ''
      . '';
    }, 'general');

    register_setting('general', 'mp_server_email_port', 'esc_attr');
    add_settings_field('mp_server_email_port', '<label for="mp_server_email_port">' . __('(Voice2App) Server Email Port', 'mp_server_email_port') . '</label>', function() {
      $value = get_option('mp_server_email_port', '');
      echo '<input class="regular-text" type="number" id="mp_server_email_port" name="mp_server_email_port" value="' . $value . '" />'
      . ''
      . '';
    }, 'general');
  }

  public static function getVoice2appEmail() {
//    return get_option('mp_voice_2_app_setting_id_email', '');
    $sett = new HookSettings();
    return $sett->settings_api->get_option("voice2app_email", "mpvoice2app");
  }

  public static function getVoice2appApiKey() {
//    return get_option('mp_voice_2_app_setting_id_password', '');
    $sett = new HookSettings();
    return $sett->settings_api->get_option("voice2app_password", "mpvoice2app");
  }

}

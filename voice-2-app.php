<?php
defined('ABSPATH') || exit();
/**
 *
 * @since             1.0.7
 * @package           aMpVoice2App
 *
 * @wordpress-plugin
 * Plugin Name:       Voice2App
 * Description:       Voice2App – With Voice2App you can allow visitors to leave voice messages directly on your website and convert any post or page to a natural voice audio.  Try our free version, no credit card required.

 * Version:           1.0.7
 * Author:            Jim Adams
 * Author URI:        https://www.adamstechsols.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       voice2app
 * Domain Path:       /languages
 */


require_once 'start.php';



spl_autoload_register(function($name) {
  $exp = explode('\\', $name);
//   var_dump("Creating",$name,$exp,plugin_dir_path( __FILE__ ),"<hr />");
  if (isset($exp[0]) && $exp[0] === 'MpVoice2App') {
    $dir = strtolower($exp[1]);
    $exp11 = preg_split('/(?=[A-Z])/', $exp[1]); //strtolower($exp[1]);
    $exp1 = "";
    foreach ($exp11 as $key => $value) {

      $now = ($value === "") ? "" : "-" . strtolower($value);
      $exp1 .= $now;
//      var_dump("<pre>", ['key' => $key, 'value' => $value,'now' => $now,'exp1' => $exp1],"</pre>");
    }
//    $exp1 = strtolower($exp[1]);
    @$exp2 = strtolower($exp[2]);
    @$exp3 = strtolower($exp[3]);
    $file = plugin_dir_path(__FILE__) . "classes/class" . $exp1 . ".php";
    $exists = (file_exists($file));
    if (!$exists) {
      $file = plugin_dir_path(__FILE__) . "classes/db/class" . $exp1 . ".php";
      $exists = (file_exists($file));
    }
    if (!$exists) {
      $file = plugin_dir_path(__FILE__) . "classes/pages/class" . $exp1 . ".php";
      $exists = (file_exists($file));
    }
    if (!$exists) {
      $file = plugin_dir_path(__FILE__) . "classes/widgets/classwidget-" . $exp2 . ".php";
      $exists = (file_exists($file));
    }
    if (!$exists) {
      $file = plugin_dir_path(__FILE__) . "classes/hooks-handlers/class" . $exp1 . ".php";
      $exists = (file_exists($file));
    }
    if (!$exists) {
      $file = plugin_dir_path(__FILE__) . "classes/includes/class" . $exp1 . ".php";
      $exists = (file_exists($file));
    }
    if (file_exists($file)) {
      require($file);
    }
  }
});



$view = new MpVoice2App\View;

register_activation_hook(__FILE__, [$view,"onActivate"]);
register_deactivation_hook(__FILE__, [$view,"onDeActivate"]);
register_uninstall_hook(__FILE__, ['MpVoice2App\View',"onUninstall"]);

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'salcode_add_plugin_page_settings_link');

function salcode_add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'options-general.php?page=voice2app' ) .
		'">' . __('Settings') . '</a>';
	return $links;
}



//############## modify
function wpb_demo_shortcode() {
	$options = get_option("mpvoice2app");
	if($options['voice2app_popup_image']) {
		$src = $options['voice2app_popup_image'];
	} else {
		$actual_link = plugin_dir_url( __FILE__ );
		$src = $actual_link.'assets/images/smallspeaker.png';
	}
	return '<a href="#voice2app-popup" onclick="PUM.open(1500); return false;">
			<img class="alignnone  wp-image-342" src="' .$src. '" alt="" width="130px" height="130px">
		</a>';
	
}
// register shortcode
add_shortcode('voice2app-popup', 'wpb_demo_shortcode');

add_action('wp_footer', 'add_modal');
function add_modal () {
	$options = get_option("mpvoice2app");
	
	$size = $options['voice2app_popup_size'];
	switch ($size) {
		case 0:
			$val = 'pum-responsive-micro size-micro';
			break;
		case 1:
			$val = 'pum-responsive-tiny size-tiny';
			break;
		case 2:
			$val = 'pum-responsive-small size-small';
			break;
		case 3:
			$val = 'pum-responsive-xlarge size-xlarge';
			break;
		default:
			$val = 'pum-responsive-small size-small';
	}
	
	
	if($options['voice2app_popup_name']) {
		$title = $options['voice2app_popup_name'];
	} else {
		$title = 'Voice Message';
	}

	$actual_link = plugin_dir_url( __FILE__ );
	$src = $actual_link.'assets/images/Bars-1s-58px.gif';
	$src2 = $actual_link.'assets/images/Spinner-1s-58px.gif';
	
	echo '
				<div id="pum-1500" class="pum pum-overlay pum-theme-9 pum-theme-default-theme popmake-overlay click_open voice-modal" data-popmake="{&quot;id&quot;:15,&quot;slug&quot;:&quot;new&quot;,&quot;theme_id&quot;:9,&quot;cookies&quot;:[{&quot;event&quot;:&quot;on_popup_close&quot;,&quot;settings&quot;:{&quot;name&quot;:&quot;pum-15&quot;,&quot;key&quot;:&quot;&quot;,&quot;session&quot;:false,&quot;time&quot;:&quot;1 month&quot;,&quot;path&quot;:true}}],&quot;triggers&quot;:[{&quot;type&quot;:&quot;click_open&quot;,&quot;settings&quot;:{&quot;cookie_name&quot;:[&quot;pum-15&quot;],&quot;extra_selectors&quot;:&quot;&quot;}}],&quot;mobile_disabled&quot;:null,&quot;tablet_disabled&quot;:null,&quot;meta&quot;:{&quot;display&quot;:{&quot;stackable&quot;:false,&quot;overlay_disabled&quot;:false,&quot;scrollable_content&quot;:false,&quot;disable_reposition&quot;:false,&quot;size&quot;:&quot;medium&quot;,&quot;responsive_min_width&quot;:&quot;0%&quot;,&quot;responsive_min_width_unit&quot;:false,&quot;responsive_max_width&quot;:&quot;100%&quot;,&quot;responsive_max_width_unit&quot;:false,&quot;custom_width&quot;:&quot;640px&quot;,&quot;custom_width_unit&quot;:false,&quot;custom_height&quot;:&quot;380px&quot;,&quot;custom_height_unit&quot;:false,&quot;custom_height_auto&quot;:false,&quot;location&quot;:&quot;center top&quot;,&quot;position_from_trigger&quot;:false,&quot;position_top&quot;:&quot;100&quot;,&quot;position_left&quot;:&quot;0&quot;,&quot;position_bottom&quot;:&quot;0&quot;,&quot;position_right&quot;:&quot;0&quot;,&quot;position_fixed&quot;:false,&quot;animation_type&quot;:&quot;fade&quot;,&quot;animation_speed&quot;:&quot;350&quot;,&quot;animation_origin&quot;:&quot;center top&quot;,&quot;overlay_zindex&quot;:false,&quot;zindex&quot;:&quot;1999999999&quot;},&quot;close&quot;:{&quot;text&quot;:&quot;&quot;,&quot;button_delay&quot;:&quot;0&quot;,&quot;overlay_click&quot;:false,&quot;esc_press&quot;:false,&quot;f4_press&quot;:false},&quot;click_open&quot;:[]}}" role="dialog" aria-hidden="true">
					<div id="popmake-1500" class="pum-container popmake theme-9 pum-responsive responsive ' .$val .' " style="  border: 0px;border-radius: 0px;">
			        <div id="pum_popup_title_15" class="pum-title popmake-title">' .$title. '</div>
			        <div class="pum-content popmake-content">
		            <style>
		                .pum-container.pum-responsive.pum-responsive-small {
		                    margin-left: -20%!important;
		                    width: 40%!important;
		                    border: 0px !important;
		                }
		                .voice-modal .pum-content + .pum-close {
										  background-color: rgb(8, 100, 178);
										position: absolute;
									  top: 10px;
									  right: 10px;
									  border-radius: 100%;
									  width: 60px;
									  height: 60px;
									  font-size: 15px;
									  padding: 0;
									  color: #fff;
									}
									
									.voice-modal  .pum-title {
										font-weight: 200;
									  color: #1264b2;
									}
									.voice-modal label {
										font-weight: 700;
										font-size: 16px;
									    color: #000000;
									    margin-top: 15px;
									}

									
		            </style>
		            <div id="success" style="display:none" class="message-success">
		                Your voice message has been sent successfully.
		            </div>
		            <div id="panel" class="panel">
		                <label style="padding-left:5px;padding-top:20px">Voice Message * </label>
						<br/><br/>
						<div id="control-container" style="display:flex">
							<button class="btn btn-success" id="start-recording" onclick="startRecording()"><i class="fas fa-microphone-alt" style="font-size:20px;color:black;padding:5px 2px" ></i></button>
							&nbsp;
		                    <button class="btn btn-danger" id="stop-recording" disabled onclick="stopRecording()"><i class="fas fa-stop" style="font-size:20px;color:black;padding:5px 2px" ></i></button>
							<label id="lblRecording" style="padding-left:10px;display:none">Recording ...</label>
							<img id="imgLoader" style="padding-left:5px;display:none" src="' .$src. '"/>
						
						</div>
		                <div id="message-container" style="display:none;margin-top:1px;width:80%;">
							<audio id="audio-element" preload="auto" controls src=""></audio>
							&nbsp;
							<button	class="btn btn-danger" id="delete-recording" onclick="deleteRecording()"><i class="fas fa-trash"  style="color:black;padding-top:5px;"></i></button>							
		                </div>
		                <table class="border-none">
		                    <tbody>
		                    <tr style="">
		                        <td style="width:90%;padding-top:5px">
		                            <label for="dscr">Description: *</label>
		                    		<input id="dscr" style="width:200px" placeholder="Enter Description"  type="text" />
									<span style="display:none" id="description-error" class="error-message">Description is required</span>
		                        </td>
		                    </tr>
		                    <tr style="">
		                        <td style="width:90%">
		                            <label for="email">Email: *</label>
		                            <input id="email" required="" style="width:200px" placeholder="Enter Email"  type="email" />
		                            <span style="display:none" id="email-error" class="error-message">Valid Email is required</span>
		                        </td>
							</tr>
							<tr style="">
		                        <td style="width:90%">
		                            <button class="btn btn-submit" id="save-recording" style="width: auto !important;" disabled onclick="saveRecording()">Submit</button>
									<img id="imgLoader2" style="padding-left:5px;display:none" src="' .$src2. '"/> 
								</td>
		                    </tr>
		                    </tbody>
						</table>	               
		            </div>
		        </div> <!-- popmake-content -->
		        <button type="button" class="pum-close popmake-close" aria-label="Close">X</button>
		    </div>
		</div>
	';
}

function voice2app_enqueue_scripts() {
	$active_is_popup = in_array('popup-maker/popup-maker.php', (array)get_option('active_plugins', array()));

	if (!$active_is_popup) {
		$base = plugin_dir_url(__FILE__);
		wp_enqueue_style('popup_ss5', $base . "/assets/css/pum-site-styles.css", [], '1.0');
		wp_enqueue_script('popup_general', $base ."/assets/js/popup.min.js", ['jquery'], '1.0', true);
	}
}
add_action('wp_enqueue_scripts', 'voice2app_enqueue_scripts');
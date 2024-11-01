<?php

namespace MpVoice2App\Widget;

use MpVoice2App\Common;

/**
 * Description of class-widget-sponsor
 *
 * @author Jim Adams
 * Created on : May 26, 2019, 1:25:50 PM
 */
class Delivery extends \WP_Widget {

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'mp_delivery_service', // Base ID
      esc_html__('(Delivery Service) The delivery service widget'), // Name
      array('description' => esc_html__('The delivery service widget', 'text_domain'),) // Args
    );
  }


  public function widget($args, $instance) {

    $code = "";

    $fname = Common::$PLUGIN_DIR . "/templates/widgets/delivery-service.php";
    ob_start();
    require $fname;
    $code = ob_get_clean();
    echo $args['before_widget'];
    echo $code;
    echo $args['after_widget'];
  }

  public function formm($instance) {
//    $title = !empty($instance['title']) ? $instance['title'] : esc_html__('New title', 'text_domain');
    $title = !empty($instance['id']) ? $instance['id'] : esc_html__('ShortCode', 'text_domain');
//  
  }

  public function form($instance) {
   
  }

  public function update($new_instance, $old_instance) {
    $instance = array();
    $instance['widget_code'] = (!empty($new_instance['widget_code']) ) ? sanitize_text_field($new_instance['widget_code']) : '';

    return $instance;
  }

}

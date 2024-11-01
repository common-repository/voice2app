<?php

namespace MpVoice2App;

class HookPostMetaBox {

  function __construct() {
    add_action("add_meta_boxes", [$this, "add_meta_boxes_func"]);
    add_action("save_post", [$this, "save_narration"], 10, 3);
  }

  
  function add_meta_boxes_func() {
    add_meta_box("mp-voice2app", "(Voice2App) Narration", function() {
      global $post;
      
      $fname = Common::$PLUGIN_DIR . "/templates/sections/section-post-meta-box.php";
      $code = Common::getContents($fname);
      echo $code;
    }, null, "advanced", "high", null);
  }


  function save_narration($post_id, $post, $update) {
    update_post_meta( $post_id, 'voice2app_narration', sanitize_text_field($_POST['voice2app_narration']));
  }

}

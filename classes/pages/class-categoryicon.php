<?php

namespace MpVoice2App;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CategoryIcon extends General {

  public function __construct() {
//    add_filter("mp_get_category_icon", [$this, "get_category_icon"], 10, 1);
//    add_filter("mp_mw_get_sponsors_name", [$this, "get_good_shortcode_name"]);
    parent::__construct();
  }

  /**
   * 
   * @param array [category_icon_image_id,category_icon_image_url)
   */
  public function get_category_icon_image_id($category_id) {
    $icon_id = "";
    $data = [
      Db::w => ["*"],
      Db::h => [
        Db::ICON_CAT_ID => $category_id
      ],
      Db::l => 1
    ];
//    die();
    $get = Db::$DB_ICONS->get($data);
    if ($get) {
      $icon_url = wp_get_attachment_url($get[Db::ICON_IMAGE_ID]);
      $icon_id = $get[Db::ICON_IMAGE_ID];
    }
    return $icon_id;
  }
  public function get_category_icon_image_url($category_id) {
    $icon_url = "";
    $data = [
      Db::w => ["*"],
      Db::h => [
        Db::ICON_CAT_ID => $category_id
      ],
      Db::l => 1
    ];
    $get = Db::$DB_ICONS->get($data);
    if ($get) {
      $icon_url = wp_get_attachment_url($get[Db::ICON_IMAGE_ID]);
    }
    return $icon_url;
  }

  public function save_icons($post) {
//    Common::eko([
//      'crt icon ' => $post
//    ]);
    $all = $post[Common::VAR_1];
    $error_occured = false;
    foreach ($all as $cat) {
      $cat_id = $cat['cat_ID'];
      $cat_icon_id = $cat['iconImageId'];
      $exist = Db::$DB_ICONS->get([
        Db::w => ["*"],
        Db::h => [
          Db::ICON_CAT_ID => $cat_id
        ]
      ]);
      if ($exist) {
        $update = Db::$DB_ICONS->update([
          Db::w => [Db::ICON_IMAGE_ID => $cat_icon_id],
          Db::h => [Db::ICON_CAT_ID => $cat_id]
        ]);
        if (!$update) {
          $error_occured = true;
        }
      } else {
        $save = Db::$DB_ICONS->insert([
          Db::ICON_CAT_ID => $cat_id,
          Db::ICON_IMAGE_ID => $cat_icon_id
        ]);
        if (!$save) {
          $error_occured = true;
        }
      }
    }
    $view = new View();
    if ($error_occured) {
      do_action(self::BAD, $view->getAllCategories(), "An error occured while Saving some items");
    } else {
      do_action(self::GOOD, $view->getAllCategories(), 'All Saved Successfully');
    }
  }

}

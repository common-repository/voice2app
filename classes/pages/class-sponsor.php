<?php

namespace Mp_My_Widgets;

/**
 * Description of class-sponsor
 *
 * @author MACHINE PEREERE
 * Created on : May 26, 2019, 10:57:37 AM
 */
class Sponsor extends General {

  public function __construct() {
    add_filter("mp_my_widgets_get_sponsors", [$this, "get_all_sponsors"]);
    add_filter("mp_mw_get_sponsors_name", [$this, "get_good_shortcode_name"]);
    add_filter("mp_mw_get_good_shortcode_name", [$this, "get_good_shortcode_name"]);
    parent::__construct();
  }

  public function delete_sponsor($post) {
//    Common::eko([
//        'delete spo' => $post
//    ]);
    $ids = $post[Common::VAR_1];
    foreach ($ids as $target_sponsor_id) {
      $delete = Db::$DB_SPONSORS->delete([Db::SPONSOR_ID => $target_sponsor_id]);
      if (!$delete) {
        do_action(self::BAD, $this->get_admin_object(), "An error occured while deleting Sponsor");
      }
      $delete = Db::$DB_SPONSORS_IMAGES->delete([Db::SPONSOR_IMAGE_SPONSOR_ID => $target_sponsor_id]);
      if (!$delete) {
        do_action(self::BAD, $this->get_admin_object(), "An error occured while deleting Sponsor");
      }
    }
    do_action(self::GOOD, $this->get_admin_object(), 'Sponsor(s) Deleted Successfully');
  }

  public function update_sponsor($post) {
//    Common::eko([
//        'update spo' => $post
//    ]);
    $ids = $post[Common::VAR_1];
    $sponsors = $post[Common::VAR_2];
    foreach ($ids as $target_sponsor_id) {
      foreach ($sponsors as $sponsor) {
        $sponsor_id = $sponsor['id'];
        if ($sponsor_id === $target_sponsor_id) {
          $display_as_link = $sponsor['display_url'] ? '1' : '0';
          $update = Db::$DB_SPONSORS->update([
              Db::w => [Db::SPONSOR_DISPLAY_AS_LINKS => $display_as_link],
              Db::h => [Db::SPONSOR_ID => $sponsor_id]
          ]);
          if (!$update) {
            do_action(self::BAD, $this->get_admin_object(), "An error occured while updating Sponsor");
          }
          $sponsor_images = $sponsor['sponsors'];
          $delete = Db::$DB_SPONSORS_IMAGES->delete([
              Db::SPONSOR_IMAGE_SPONSOR_ID => $sponsor_id
          ]);
          if (!$delete) {
            do_action(self::BAD, $this->get_admin_object(), "An error occured while updating Sponsor");
          }
//          Common::eko([
//              'simages' => $sponsor_images,
//              'sponsors' => $sponsors
//          ]);
          foreach ($sponsor_images as $sponsor_image) {
            $image_id = $sponsor_image['id'];
            $url = $sponsor_image['url'];
            $insert = Db::$DB_SPONSORS_IMAGES->insert([
                Db::SPONSOR_IMAGE_SPONSOR_ID => $sponsor_id,
                Db::SPONSOR_IMAGE_IMAGE_ID => $image_id,
                Db::SPONSOR_IMAGE_URL => $url
            ]);
            if (!$insert) {
              do_action(self::BAD, $this->get_admin_object(), "An error occured while updating Sponsor images");
            }
          }
        }
      }
    }
    do_action(self::GOOD, $this->get_admin_object(), 'Sponsor(s) Updated Successfully');
  }

  public function create_sponsor($post) {
//    Common::eko([
//        'create p' => $post
//    ]);
    $all = $post[Common::VAR_1];
    $name = trim($all['name']);
    $as_link = $all['display_url'] ? '1' : '0';
    $sponsors = $all['sponsors'];
    if ($this->sponsor_exists($name)) {
      do_action(self::BAD, false, "Sponsor Name Already Exists");
    }
    if (count($sponsors) < 1) {
      do_action(self::BAD, false, "Please add at leaset one picture");
    }
    $create_sponsor = Db::$DB_SPONSORS->insert([
        Db::SPONSOR_NAME => $name,
        Db::SPONSOR_DISPLAY_AS_LINKS => $as_link,
        Db::SPONSOR_CREATED_DATETIME => Common::getDateTime(),
        Db::SPONSOR_USER_ID => get_current_user_id()
    ]);
    if ($create_sponsor) {
      $sponsor_id = Db::$insertedId;
      foreach ($sponsors as $value) {
        $image_id = $value['image_id'];
        if ($image_id < 1) {
          do_action(self::BAD, $this->get_admin_object(), "Please provide an image to any");
        }
        $url = $value['url'];
        $insert = Db::$DB_SPONSORS_IMAGES->insert([
            Db::SPONSOR_IMAGE_IMAGE_ID => $image_id,
            Db::SPONSOR_IMAGE_SPONSOR_ID => $sponsor_id,
            Db::SPONSOR_IMAGE_URL => $url,
        ]);
        if (!$insert) {
          do_action(self::BAD, $this->get_admin_object(), "An error occured while creating a sponsor");
        }
      }
    }
//    do_action(self::BAD,$this->get_admin_object(),'Sponsor Not Created ');
    do_action(self::GOOD, $this->get_admin_object(), 'Sponsor Created Successfully');
  }

  /**
   * Get the sponsor object
   * 
   * @return array
   */
  public function get_all_sponsors() {
    $all = Db::$DB_SPONSORS->get([
        Db::w => ["*"],
        Db::h => [
            Db::SPONSOR_DELETED => '0'
        ],
        Db::od => Db::SPONSOR_ID
    ]);
    $to_ret = [];
    $sponsors = [];

    if ($all) {
      foreach ($all as $value) {
        $sponsor_as_link = $value[Db::SPONSOR_DISPLAY_AS_LINKS] === '1' ? true : false;
        $sponsor_id = $value[Db::SPONSOR_ID];
        $sponsor_name = $value[Db::SPONSOR_NAME];
        $sponsor_images = [];

        $db_image = Db::$DB_SPONSORS_IMAGES->get([
            Db::w => ["*"],
            Db::h => [
                Db::SPONSOR_IMAGE_DELETED => '0',
                Db::SPONSOR_IMAGE_SPONSOR_ID => $sponsor_id
            ]
        ]);
//        Common::in_script([
//            'sp id' => $sponsor_id,
//            'sp nam' => $sponsor_name,
//            'db' => $db_image
//        ]);
        if ($db_image) {
          foreach ($db_image as $sp_image) {
            $sponsor_image_id = (int) $sp_image[Db::SPONSOR_IMAGE_ID];
            $image_id = $sp_image[Db::SPONSOR_IMAGE_IMAGE_ID];
            $image_url = wp_get_attachment_image_src($image_id, 'full')[0];
            $url = $sp_image[Db::SPONSOR_IMAGE_URL];
            $sponsor_images[] = [
                'image_id' => $sponsor_image_id,
                'id' => $image_id,
                'url' => $url,
                'image_url' => $image_url
            ];
          }
          $sponsors[] = [
              'name' => $sponsor_name,
              'id' => $sponsor_id,
              'display_url' => $sponsor_as_link,
              'sponsors' => $sponsor_images
          ];
        }
      }
    }
    return $sponsors;
  }

  public function get_good_shortcode_name($name) {
    $short = trim(str_replace(" ", "_", strtolower(trim($name))));
    return $short;
  }

}

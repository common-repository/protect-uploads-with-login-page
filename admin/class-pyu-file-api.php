<?php 

class PYU_File_Api {

  private $post_id;
  private $post;

  function __construct($post_id) {
    $this->post_id = $post_id;
    $this->post = get_post($post_id);
  }

  public function get_post_id() {
    return $this->post_id;
  }

  /* returns the filename without path like document.pdf */
  public function get_filename() {
    return pathinfo($this->post->guid)['basename'];
  }

  public function get_guid_from_db() {
    global $wpdb;
    $post_id = esc_sql($this->post_id);
    $attachment_row = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix. "posts WHERE ID = " . $post_id);
    return $attachment_row->guid;
  }

  public function get_user_id_meta_data() {
    $value_user_id = get_post_meta($this->post_id, '_pyu_meta_user_id', true);
    if (!$value_user_id) return false;
    return $value_user_id;
  }

  public function get_is_protected() {
    return $this->file_exists_at_attached_file_data() && 
      $this->attached_file_data_in_protected_area() && 
      $this->get_guid_in_protected_area() &&
      $this->get_protected_meta();
  }

  public function get_protected_meta() {
    $value_activated = get_post_meta($this->post_id, '_pyu_meta_activated', true);
    if (!$value_activated) return false;
    else return true;
  }

  public function set_protected_meta($user_id) {
    update_post_meta($this->post_id, '_pyu_meta_activated', 1);
    update_post_meta($this->post_id, '_pyu_meta_user_id', $user_id);
  }

  public function set_unprotect_file_meta() {
    update_post_meta($this->post_id, '_pyu_meta_activated', 0);
    delete_post_meta($this->post_id, '_pyu_meta_user_id');
  }

  public function file_exists_at_attached_file_data() {
    $meta_attached_file = get_post_meta($this->post_id, '_wp_attached_file', true);
    $filepath = trailingslashit(wp_upload_dir()['basedir']) . $meta_attached_file;
    if (is_file($filepath)) return true;
    return false;
  }

  public function get_guid_in_protected_area() {
    $guid = self::get_guid_from_db();
    $preg_pattern = "/^(.+)\/(" . PYU_Config::$upload_dir . ")\/(.+)$/";
    if (preg_match($preg_pattern, $guid)) return true;
    else return false;
  }

  public function attached_file_data_in_protected_area() {
    $meta_attached_file = get_post_meta($this->post_id, '_wp_attached_file', true);
    $preg_pattern = "/^(" . PYU_Config::$upload_dir . ")\/(.+)$/";
    if (preg_match($preg_pattern, $meta_attached_file)) return true;
    return false;
  }

  public function move_file_to_protected_area() {

    if (get_post_type($this->post_id) !== 'attachment') {
      return false;
    }

    $new_upload_dir = trailingslashit(wp_upload_dir()['basedir']) . PYU_Config::$upload_dir;
    $original_filepath = get_post_meta($this->post_id, '_wp_attached_file', true);
    $original_filepath = trailingslashit(wp_upload_dir()['basedir']) . $original_filepath;
    $filename = pathinfo($original_filepath)['basename'];
    $new_filepath = $new_upload_dir . '/' . $filename;
    
    // check if upload directory exists
    if (!is_dir($new_upload_dir)) {
      mkdir($new_upload_dir, 0777, true);
    }

    if (is_file($original_filepath)) {
      if (!rename($original_filepath, $new_filepath)) {
        return false;
      }
    }

    if (is_file($new_filepath)) {
      return true;
    }

    return false;
  }


  public function move_attached_file_and_guid() {
    global $wpdb;
    $original_filepath = get_post_meta($this->post_id, '_wp_attached_file', true);
    $filename = pathinfo($original_filepath)['basename'];
    $new_filepath_upload = PYU_Config::$upload_dir . '/' . $filename;
    $new_guid = wp_upload_dir()['baseurl'] . '/' . PYU_Config::$upload_dir . '/' . $filename;
    update_post_meta($this->post_id, '_wp_attached_file', $new_filepath_upload);
    $wpdb->update($wpdb->posts, ['guid' => $new_guid], ['ID' => $this->post_id]);
  }



}
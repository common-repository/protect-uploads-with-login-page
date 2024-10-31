<?php


class PYU_Uploads_Controller {

  public $request_handler;

  function __construct()
  {
    require_once('class-pyu-handle-request.php');
    $this->request_handler = new PYU_FileRequestHandler();
  }

  public function admin_enqueue_scripts($hook) {
    // print_r($hook);

    if ($hook == 'protect-your-uploads_page_protect-your-uploads-files') {

      wp_enqueue_script('pyu_ajax_save_script', plugin_dir_url( __FILE__ ) . 'js/pyu_save_ajax.js', array('jquery'), '0.1', true);
      $translation_array = array(
        'protected' => __('Protected', 'protect_your_uploads'),
        'unprotected' => __('Unprotected', 'protect_your_uploads'),
        'update_successfull' =>  __('Update successful', 'protect_your_uploads'),
        'update_error' => __('An error occured', 'protect_your_uploads')
      );
      wp_localize_script('pyu_ajax_save_script', 'my_translation', $translation_array);
      wp_enqueue_script('toastr_js', plugin_dir_url( __FILE__ ) . '../toastr/toastr.min.js', array('jquery'), '0.1');
      wp_enqueue_style('toastr_css', plugin_dir_url( __FILE__ ) . '../toastr/toastr.min.css', array(), '0.1');

    }
  }

  public function handle_ajax_request_dashboard_save() {

    // require_once('./class-pyu-upload-controller.php');
    require_once('class-pyu-file-api.php');
    require_once('class-pyu-cpt-user-api.php');
    
    /*
      file id/post id
      protect file yes or no
      user id
    */

    if (!isset($_POST['post_id']) || !isset($_POST['user_id']) || !isset($_POST['protect'])) {
      wp_send_json(array(), 400); // bad request
    }
    
    $post_id = (int) $_POST['post_id'];
    $user_id = (int) $_POST['user_id'];
    $protect_file = (int) $_POST['protect'];

    if ($protect_file) {
      $ret_value = $this->protect_file($post_id, $user_id);
      if (!$ret_value) {
        wp_send_json(array(), 500); // internal server error
      }
    }
    else {
      $this->unprotect_file($post_id, $user_id);
    }

    
    $file_api = new PYU_File_Api($post_id);

    $response = array();
    $response['protected'] = $file_api->get_is_protected();
    $response['post_id'] = (int) $post_id;
    $response['guid'] = $file_api->get_guid_from_db();

    if ($response['protected']) {
      $response['user_id'] = $file_api->get_user_id_meta_data();
      $response['username'] = (new PYU_User_API($response['user_id']))->get_username();
    }
    else {
      $response['user_id'] = $user_id;
      $response['username'] = (new PYU_User_API($user_id))->get_username();
    }

    wp_send_json($response);

  }

  public function protect_file($post_id, $user_id) {
    require_once('class-pyu-file-api.php');
    $file_api = new PYU_File_Api($post_id);
    
    if (!$file_api->get_guid_in_protected_area() && !$file_api->attached_file_data_in_protected_area()) {
      $result_move = $file_api->move_file_to_protected_area();
      if ($result_move) {
        $file_api->move_attached_file_and_guid();
      }
      else {
        return false;
      }
    }

    if ($file_api->get_guid_in_protected_area() && $file_api->attached_file_data_in_protected_area()) {
      $file_api->set_protected_meta($user_id);
    }
    else {
      return false;
    }

    return true;

  }

  public function unprotect_file($post_id) {
    require_once('class-pyu-file-api.php');
    $file_api = new PYU_File_Api($post_id);
    $file_api->set_unprotect_file_meta();
  }

  /* Has to be moved to public plugin directory */
  public function handle_file_request($query) {
    $this->request_handler->handle_request($query);
  }

  public function select_template($template) {
    return $this->request_handler->select_template($template);
  }

  public function display_file_dashboard() {

    require_once('class-pyu-cpt-user-api.php');
    require_once('class-pyu-uploads-api.php');
    require_once('class-pyu-file-api.php');
    require_once('class-pyu-cpt-users-api.php');
    
    $uploads_api = new PYU_Uploads_API();
    $file_rows = array();
    $users_api = new PYU_Users_API();
    $users = $users_api->get_users();

    foreach ($users as $user) {
      $user->username = (new PYU_User_API($user->ID))->get_username();
    }

    foreach ($uploads_api->get_uploads() as $upload) {

      $row = array();
      $post_id = $upload->ID;
      $file_api = new PYU_File_Api($post_id);
      $user_id = $file_api->get_user_id_meta_data();
      $user_api = new PYU_User_API($user_id);

      $row['post_id'] = $post_id;
      $row['filename'] = $file_api->get_filename();
      $row['guid'] = $upload->guid;
      $row['user_id'] = $user_id;
      $row['username'] = $user_api->get_username();
      $row['protected'] = $file_api->get_is_protected();

      $file_rows[] = $row;

    }

    // setup variables

    require('partials/file_dashboard/file_dashboard.php');

  }

  public function delete_custom_user($user_id) {
    $post_type = get_post_type($user_id);
    if (!get_post_type($user_id) == 'pyu-user') {
      return;
    }

    require_once('class-pyu-uploads-api.php');
    require_once('class-pyu-file-api.php');

    $uploads_api = new PYU_Uploads_API();
    foreach ($uploads_api->get_post_IDs() as $upload_id) {
      $file_api = new PYU_File_Api($upload_id);
      if ($file_api->get_user_id_meta_data() == $user_id) {
        if ($file_api->get_protected_meta()) {
          $this->unprotect_file($upload_id);
        }
      }
    }

  }


}
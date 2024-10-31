<?php 


class PYU_FileRequestHandler {

  private $show_login = false;
  private $show_access_denied = false;

  function __construct()
  {
    
  }

  public function set_title($title) {
    if ($this->show_login) {
      $title = __('Protected File - Login required', 'protect_your_uploads');
    }
    else if ($this->show_access_denied) {
      $title =  __('Unauthorized - Invalid Login', 'protect_your_uploads');
    }
    return $title;
  }

  public function enqueue_styles() {
    if ($this->show_login) {
      global $wp_styles;
      $wp_styles->queue = array();
      wp_enqueue_style('pyu_bootstrap4', plugin_dir_url( __FILE__ ) . '../bootstrap/bootstrap.min.css', array(), '0.1');
      wp_enqueue_style('pyu_login_page_css', plugin_dir_url( __FILE__ ) . 'css/pyu_login_page.css');
      wp_enqueue_script('jquery');
    }
    else if ($this->show_access_denied) {
      global $wp_styles;
      $wp_styles->queue = array();
      wp_enqueue_style('pyu_bootstrap4', plugin_dir_url( __FILE__ ) . '../bootstrap/bootstrap.min.css', array(), '0.1');
      wp_enqueue_style('pyu_login_page_css', plugin_dir_url( __FILE__ ) . 'css/pyu_access_denied.css');
      wp_enqueue_script('jquery');
    }
  }

  public function select_template($template) {
    if ($this->show_login) {
      $template = PYU_PLUGIN_ADMIN_DIR . 'partials/login/login.php';
    }
    else if ($this->show_access_denied) {
      $template = PYU_PLUGIN_ADMIN_DIR . 'partials/access_denied/access_denied.php';
    }
    return $template;
  }

  public function handle_request($query) {

    require_once('class-pyu-file-api.php');
    require_once('class-pyu-cpt-user-api.php');
    require_once('class-pyu-uploads-api.php');

    if (isset($_POST['pyu_post_id']) && isset($_POST['pyu_user_name']) && isset($_POST['pyu_user_pw'])) {

      $in_post_id = (int) sanitize_text_field($_POST['pyu_post_id']);
      $in_user_name = sanitize_text_field(trim($_POST['pyu_user_name']));
      $in_user_pw = sanitize_text_field($_POST['pyu_user_pw']);

      $file_api = new PYU_File_Api($in_post_id);
      
      if ($file_api->get_is_protected()) {

        $user_id = $file_api->get_user_id_meta_data();
        $user_api = new PYU_User_API($user_id);
        $username = $user_api->get_username();
        $password = $user_api->get_password();

        if ($password && $username && $username == $in_user_name && $password == $in_user_pw) {
          // serve file
          $this->serve_file($file_api->get_post_id());
        }
        else {
          // access denied
          $this->display_access_denied_page($file_api->get_post_id());
        }


      }
      else {
        // serve file
        $this->serve_file($file_api->get_post_id());
      }


    }
    else if (!empty($query->query_vars['pyu_filename']) && !empty($query->query_vars['pyu_fileext'])) {
      
      // show login page
      $file_basename = $query->query_vars['pyu_filename'] . '.' . $query->query_vars['pyu_fileext'];
      
      // Check if file is protected
      $uploads_api = new PYU_Uploads_API();
      $post = $uploads_api->get_post_by_filename($file_basename);
            
      if (!$post) {
        http_response_code(404);
        echo('asset can not be found');
        wp_die();
      }

      $file_api = new PYU_File_Api($post->ID);

      if ($file_api->get_is_protected()) {
        $this->display_login_page($post->ID);
      }
      else {
        $this->serve_file($post->ID);
      }

    }
    
  }

  private function display_login_page($arg_post_id) {
    global $pyu_post_id;
    $pyu_post_id = $arg_post_id;
    $this->show_login = true;
    // $post_id = $arg_post_id;
    // require(PYU_PLUGIN_ADMIN_DIR . 'partials/login/login.php');
    // exit();
  }

  private function display_access_denied_page($arg_post_id) {
    $post = get_post($arg_post_id);
    if (!$post) {
      http_response_code(404);
      echo('asset can not be found');
      wp_die();
    }
    global $pyu_filepath_url;
    $pyu_filepath_url = $post->guid;
    $this->show_access_denied = true;
    // $filepath_url = $post->guid;
    // require(PYU_PLUGIN_ADMIN_DIR . 'partials/access_denied/access_denied.php');
    // exit();
  }

  private function serve_file($arg_post_id) {

    $post = get_post($arg_post_id);
    if (!$post) {
      http_response_code(404);
      echo('asset can not be found');
      wp_die();
    }

    $mime_is_pdf = ($post->post_mime_type == 'application/pdf');
    $mime_contains = function($arg_test_string) use ($post) {
      return strstr($post->post_mime_type, $arg_test_string);
    };

    $upload_dir = wp_upload_dir()['basedir'];
    $filepath = trailingslashit($upload_dir) . trailingslashit(PYU_Config::$upload_dir) . pathinfo($post->guid)['basename'];
    $filesize = filesize($filepath);
    $date_last_modified = gmdate('D, d M Y H:i:s', filemtime($filepath));
    $date_expiring = gmdate('D, d M Y H:i:s', time() + 60*60*24*365);
    $etag = md5($date_last_modified);

    if (!$mime_contains('video/') && !$mime_contains('audio/') && !$mime_contains('image/') && !$mime_contains('text/') && !$mime_is_pdf) {
      header('Content-Disposition: attachment; filename='.pathinfo($post->guid)['basename']);
    }
    header('Content-Type: ' . $post->post_mime_type);
    header('Content-Length: ' . $filesize);
    header('Last-Modified: ' . $date_last_modified . ' GMT');
    header('Expires: ' . $date_expiring . ' GMT');
    header('ETag: "' . $etag . '"');
    http_response_code(200);   
    readfile($filepath);
    exit();
  }

}



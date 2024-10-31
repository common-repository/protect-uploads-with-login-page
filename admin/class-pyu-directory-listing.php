<?php


class PYU_Directory_Listing
{

  function __construct()
  {

  }

  private function get_index_filepath() {
    $protected_upload_dir = trailingslashit(wp_upload_dir()['basedir']) . PYU_Config::$upload_dir;
    $index_file = trailingslashit($protected_upload_dir) . 'index.php';
    return $index_file;
  }

  private function add_index_file() {
    file_put_contents($this->get_index_filepath(), '<?php');
  }

  private function remove_index_file() {
    unlink($this->get_index_filepath());
  }

  private function index_file_exists() {
    $index_filepath = $this->get_index_filepath();
    if (is_file($index_filepath)) {
      return true;
    }
    else {
      return false;
    }
  }

  private function disable_directory_browsing() {
    $options = get_option('pyu_plugin_options');
    if (isset($options['disable_directory_browsing'])) {
      if ($options['disable_directory_browsing']) {
        return true;
      }
      else {
        return false;
      }
    }
    else {
      return false;
    }
  }

  private function upload_dir_is_writable() {
    $protected_upload_dir = trailingslashit(wp_upload_dir()['basedir']) . PYU_Config::$upload_dir;
    if (is_writable($protected_upload_dir)) return true;
    else return false;
  }

  public function init()
  {
    if ($this->disable_directory_browsing() && !$this->index_file_exists() && $this->upload_dir_is_writable()) {
      $this->add_index_file();
    }
    if (!$this->disable_directory_browsing() && $this->index_file_exists() && $this->upload_dir_is_writable()) {
      $this->remove_index_file();
    }
  }

}

?>
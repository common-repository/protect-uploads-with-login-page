<?php

class PYU_Uploads_API {

  private $attachments;
  private $post_ids;

  function __construct()
  {
    $this->attachments = array();
    $this->post_ids = array();
    $this->load_uploads();
    $this->load_uploads_post_IDs();
  }

  private function load_uploads() {
    $args = array(
      'numberposts' => -1,
      'post_type' => 'attachment',
    );
    $attachments = get_posts($args);
    if ($attachments) {
      $this->attachments = $attachments;
    }
  }

  private function load_uploads_post_IDs() {
    foreach ($this->attachments as $attachment) {
      $this->post_ids[] = $attachment->ID;
    }
  }

  public function get_uploads() {
    return $this->attachments;
  }

  public function get_post_IDs() {
    return $this->post_ids;
  }

  public function get_post_by_filename($file_basename) {
    foreach($this->attachments as $attachment) {
      if (pathinfo($attachment->guid)['basename'] == $file_basename) {
        return $attachment;
      }
    }
    return false;
  }

}
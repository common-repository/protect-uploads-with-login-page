<?php

class PYU_User_API
{

  private $post_id;

  function __construct($post_id)
  {
    $this->post_id = $post_id;
    $this->post = get_post($post_id);
  }

  public function get_user_id() {
    return $this->post_id;
  }

  public function get_username() {
    $value_user_name = get_post_meta($this->post_id, '_PYU_meta_username', true);
    if (!$value_user_name) return false;
    return $value_user_name;
  }

  public function get_password() {
    $value_user_pw = get_post_meta($this->post_id, '_PYU_meta_userpw', true);
    if (!$value_user_pw) return false;
    return $value_user_pw;
  }

}

<?php

class PYU_Users_API
{

  private $users;
  private $user_IDs;

  function __construct()
  {
    $this->users = array();
    $this->user_IDs = array();
    $this->load_users();
    $this->load_users_IDs();
  }

  private function load_users() {
    $args = array(
      'numberposts' => -1,
      'post_type' => 'pyu-user',
    );
    $users = get_posts($args);
    if ($users) {
      $this->users = $users;
    }
  }

  private function load_users_IDs() {
    foreach ($this->users as $user) {
      $this->user_IDs[] = $user->ID;
    }
  }

  public function get_users() {
    return $this->users;
  }

  public function get_users_IDs() {
    return $this->user_IDs;
  }

}

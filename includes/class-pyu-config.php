<?php

abstract class PYU_Config
{

  public static $upload_dir = 'pyu_uploads';

  // WP Meta Keys
  public static $key_post_meta_protected_active = '_pyu_meta_activated';
  public static $key_post_meta_protected_user_id = '_pyu_meta_user_id';

  // Ajax Request
  public static $key_ajax_protect_action = 'pyu-protect-files';
  public static $key_ajax_post_id = 'pyu-post-id';
  public static $key_ajax_user_id = 'pyu-user-id';
  public static $key_ajax_protect_file = 'pyu-protect-file';
  // Response
  public static $key_ajax_file_in_protected_area = 'file_in_protected_area';
  public static $key_ajax_file_protected_meta = 'file_protected_meta';
  public static $key_ajax_user_id_meta = 'file_user_id_meta';
  public static $key_ajax_user_name_meta = 'file_user_name_meta';
  public static $key_ajax_guid = 'file_guid';

  // HTML IDs
  public static $html_id_main_status = 'pyu_file_status_';
  public static $html_id_main_activate_cb = 'pyu_file_activate_cb_';
  public static $html_id_main_direct_link = 'pyu_direct_link_';
  public static $html_id_main_user = 'pyu_user_';
  public static $html_id_main_user_selection = 'pyu_select_user_';
  public static $html_id_main_save_button = 'pyu_save_button_';

  // Login Page
  public static $html_login_id_post_id = 'pyu_post_id';
  public static $html_login_id_user_name = 'pyu_user_name';
  public static $html_login_id_user_pw = 'pyu_user_pw';
}

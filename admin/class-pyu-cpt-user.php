<?php

class PYU_CPT_User
{

  private $post_type_name;

  function __construct()
  {
    $this->post_type_name = 'pyu-user';
  }

  public function enqueue_scripts($hook) {
    wp_enqueue_script('pyu_user_pw_visibility', plugin_dir_url( __FILE__ ) . 'js/pyu_user_pw_visibility.js');
  }

  public function register_type()
  {
    $labels = array(
      'name'                  => __('Protect Your Uploads Users', 'protect_your_uploads'),
      'singular_name'         => $this->post_type_name,
      'menu_name'             => __('Protect Your Uploads Users', 'protect_your_uploads'),
      'name_admin_bar'        => __('Protect Your Uploads Users', 'protect_your_uploads'),
      'add_new'               => __('Add New', 'protect_your_uploads'),
      'add_new_item'          => __('Add New User', 'protect_your_uploads'),
      'new_item'              => __('New User', 'protect_your_uploads'),
      'edit_item'             => __('Edit User', 'protect_your_uploads'),
      'view_item'             => __('View User', 'protect_your_uploads'),
      'all_items'             => __('Add Users', 'protect_your_uploads'),
      'search_items'          => __('Search Users', 'protect_your_uploads'),
      'items_list_navigation' => __('Users List Navigation', 'protect_your_uploads'),
      'items_list'            => __('Users List', 'protect_your_uploads')
    );

    $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => false,
      'show_ui'            => true,
      'show_in_menu'       => false,
      'query_var'          => true,
      'rewrite'            => array('slug' => 'PYU-user'),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => null,
      'supports'           => array(''), // remove all supports
      'register_meta_box_cb' => array($this, 'add_user_meta_boxes')
    );
    register_post_type('pyu-user', $args);
  }


  public function users_submenu_page()
  {
    add_submenu_page('protect-your-uploads-menu', __('Manage Users', 'protect_your_uploads'), __('Manage Users', 'protect_your_uploads'), 'manage_options', 'edit.php?post_type=pyu-user', null, 1);
  } 

  public function custom_edit_columns($columns)
  {
    unset($columns['title']);
    unset($columns['date']);
    $columns['username'] = __('Username', 'protect_your_uploads');
    $columns['date'] = __('Date', 'protect_your_uploads');
    return $columns;
  }

  public function fill_custom_edit_columns($column, $post_id)
  {
    switch ($column) {
      case 'username':
        $value_username = get_post_meta($post_id, '_PYU_meta_username', true);
        echo '<a href="post.php?post=' . $post_id . '&action=edit">' . $value_username . '</a>';
        break;
    }
  }

  public function modify_list_row_actions($actions, $post) {
    if ($post->post_type == 'pyu-user' ) {
      unset($actions['inline hide-if-no-js']);
    }
    return $actions;
  }

  function change_primary_edit_page_column($default, $screen)
  {
    if ('edit-pyu-user' === $screen) {
      $default = 'username';
    }
    return $default;
  }

  public function add_user_meta_boxes()
  {
    add_meta_box(
      'PYU-user-meta-box-id',
      'Username and Password',
      array($this, 'meta_box_user_html'),
      $this->post_type_name,
      'normal'
    );
  }


  public function save_meta_data($post_id)
  {
    if (array_key_exists('PYU_meta_username_field', $_POST)) {
      update_post_meta(
        $post_id,
        '_PYU_meta_username',
        sanitize_text_field($_POST['PYU_meta_username_field'])
      );
    }
    if (array_key_exists('PYU_meta_userpw_field', $_POST)) {
      update_post_meta(
        $post_id,
        '_PYU_meta_userpw',
        sanitize_text_field($_POST['PYU_meta_userpw_field'])
      );
    }
  }

  public function meta_box_user_html($user)
  {
    $value_username = get_post_meta($user->ID, '_PYU_meta_username', true);
    $value_userpw = get_post_meta($user->ID, '_PYU_meta_userpw', true);
?>
    <form>
      <table>
        <tr>
          <td><label for="PYU_meta_username_field"><?php _e('Username', 'protect_your_uploads'); ?>:&nbsp;</label></td>
          <td><input type="text" name="PYU_meta_username_field" id="PYU_meta_username_field" value=<?php echo "\"{$value_username}\""; ?>></td>
        </tr>
        <tr>
          <td><label for="PYU_meta_userpw_field"><?php _e('Password', 'protect_your_uploads'); ?>:</label></td>
          <td><input type="password" name="PYU_meta_userpw_field" id="PYU_meta_userpw_field" value=<?php echo "\"{$value_userpw}\""; ?>></td>
          <td><input type="checkbox" onclick="PYU.toggle_username_password_visibility()"><?php _e('Show Password', 'protect_your_uploads'); ?></td>
        </tr>
      </table>
    </form>
<?php
  }
}

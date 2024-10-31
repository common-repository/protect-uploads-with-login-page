<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { 
	die;
}

http_response_code(401); ?>
<!DOCTYPE html>
<html <?php echo get_language_attributes(); ?>>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body>

  <div class="pyu-login-content d-flex flex-column justify-content-center align-items-center">

    <div class="form-container">

      <div class="d-flex flex-column align-items-center">
        <img class="plugin-icon" src="<?php echo plugin_dir_url(__FILE__) . '../../images/icon-256x256.png'; ?>">
        <h3 class="login-heading"><?php _e('Login', 'protect_your_uploads'); ?></h3>
      </div>

      <form action="" method="post">
        <input type="hidden" id="pyu_post_id" name="pyu_post_id" value="<?php echo $pyu_post_id; ?>">

        <div class="form-group">
          <label for="pyu_user_name"><?php _e('Username', 'protect_your_uploads'); ?>:&nbsp;</label>
          <input class="form-control" type="text" name="pyu_user_name" id="pyu_user_name" value="" placeholder="<?php _e('Username', 'protect_your_uploads'); ?>" required>
        </div>

        <div class="form-group">
          <label for="pyu_user_pw"><?php _e('Password', 'protect_your_uploads'); ?>:</label>
          <input class="form-control" type="password" name="pyu_user_pw" id="pyu_user_pw" value="" placeholder="<?php _e('Password', 'protect_your_uploads'); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary"><?php _e('Login', 'protect_your_uploads'); ?></button>
      </form>
    </div>


  </div>

</body>

</html>
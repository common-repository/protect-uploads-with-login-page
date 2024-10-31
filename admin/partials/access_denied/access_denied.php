<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { 
	die;
}

http_response_code(403);
?>
<!DOCTYPE html>
<html <?php echo get_language_attributes(); ?>>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body>
  <div class="pyu-access-content d-flex flex-column justify-content-center align-items-center">
    <h2><?php _e('Access denied', 'protect_your_uploads'); ?></h2>
    <div class="border-bottom mb-2"></div>
    <div class="mb-4"><?php _e('Invalid Credentials', 'protect_your_uploads'); ?></div>
    <a class="btn btn-primary" href="<?php echo $pyu_filepath_url; ?>"><?php _e('Try Again', 'protect_your_uploads'); ?></a>
</body>

</html>
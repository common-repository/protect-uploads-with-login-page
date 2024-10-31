<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { 
	die;
}
?>
<h1 style="margin-top: 2em; margin-bottom: 1em;"><?php _e('Protect Your Uploads - Getting Started', 'protect_your_uploads'); ?></h1>

<h3>1.&nbsp;<?php _e('Create Users', 'protect_your_uploads'); ?></h3>
<p>
  <?php
    _e('First create one or more users on the <a href="edit.php?post_type=pyu-user">Manage Users</a> page. Set a Username and a Password for each user. The Username and Password will be used later for accessing the files.', 'protect_your_uploads');
  ?>
  
  <?php
    $language_code = get_locale();
    if ($language_code == "de_DE") {
      // load german image
      ?>
      <br>
      <img style="padding-top: 2em; padding-left: 2em;" height="200" src="<?php echo plugin_dir_url(__FILE__) . '../../images/faq_1_de.png'; ?>">
      <?php
    }
    else {
      // load english image
      ?>
      <br>
      <img style="padding-top: 2em; padding-left: 2em;" height="200" src="<?php echo plugin_dir_url(__FILE__) . '../../images/faq_1.png'; ?>">
      <?php
    }
  ?>
</p>

<h3 style="margin-top: 2em;">2.&nbsp;<?php _e('Protect Your Files', 'protect_your_uploads'); ?></h3>
<p>
  <?php
    _e('In the <a href="admin.php?page=protect-your-uploads-files">Files Overview</a> page you can protect and unprotect easily. Set or unset the checkbox, select a user and click the "Save" button.', 'protect_your_uploads');
  ?>
   
  <?php
    $language_code = get_locale();
    if ($language_code == "de_DE") {
      // load german image
      ?>
      <br>
      <img style="padding-top: 2em; padding-left: 2em;" height="200" src="<?php echo plugin_dir_url(__FILE__) . '../../images/faq_2_de.png'; ?>">
      <?php
    }
    else {
      // load english image
      ?>
      <br>
      <img style="padding-top: 2em; padding-left: 2em;" height="200" src="<?php echo plugin_dir_url(__FILE__) . '../../images/faq_2.png'; ?>">
      <?php
    }
  ?>

</p>

<h3 style="margin-top: 2em;">3.&nbsp;<?php _e('Try To Access Your Files', 'protect_your_uploads'); ?></h3>
<p>
  <?php
    _e('Check if the protection works using the direct link shown on the <a href="admin.php?page=protect-your-uploads-files">Files Overview</a> page.', 'protect_your_uploads');
  ?>

  <?php
    $language_code = get_locale();
    if ($language_code == "de_DE") {
      // load german image
      ?>
      <br>
      <img style="padding-top: 2em; padding-left: 2em;" height="200" src="<?php echo plugin_dir_url(__FILE__) . '../../images/faq_3_de.png'; ?>">
      <?php
    }
    else {
      // load english image
      ?>
      <br>
      <img style="padding-top: 2em; padding-left: 2em;" height="200" src="<?php echo plugin_dir_url(__FILE__) . '../../images/faq_3.png'; ?>">
      <?php
    }
  ?>  
</p>

<h3 style="margin-top: 4em;"><?php _e('Support', 'protect_your_uploads'); ?></h3>
<p>
  <?php _e('If you have any questions or proposals, write to <a href="mailto:protectyouruploads@gmail.com">protectyouruploads@gmail.com</a>', 'protect_your_uploads'); ?>
</p>
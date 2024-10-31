<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
  die;
}
?>
<h1 style="margin-bottom: 2em;"><?php _e('Protect Your Upload - Settings', 'protect_your_uploads'); ?></h1>

<form action="options.php" method="post">
  <?php
  // output security fields for the registered setting "wporg"
  settings_fields('pyu_plugin_settings');
  // output setting sections and their fields
  // (sections are registered for "wporg", each field is registered to a specific section)
  do_settings_sections('protect-your-uploads-settings');
  // output save settings button
  submit_button(__('Save', 'protect_your_uploads'));
  ?>
</form>
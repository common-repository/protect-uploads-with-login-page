<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    protect_your_uploads
 * @subpackage protect_your_uploads/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    protect_your_uploads
 * @subpackage protect_your_uploads/includes
 * @author     Your Name <email@example.com>
 */
class Protect_Your_Uploads_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
    $default_options = array(
      "pyu_settings_field_googlebot" => "1",
      "pyu_settings_field_facebook_bot" => "1",
      "pyu_settings_field_twitter_bot" => "1",
      "disable_directory_browsing" => "1"
    );
    update_option("pyu_plugin_options", $default_options);
		update_option('pyu_update_htaccess_files', true);
		flush_rewrite_rules();
	}

}

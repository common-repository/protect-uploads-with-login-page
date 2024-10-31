<?php
/**
 * Plugin Name:       Protect Uploads with Login Page
 * Description:       Secure your uploads from direct access with a login page. Create users and passwords and assign them to your files.
 * Version:           1.8
 * Author:            protectyouruploads
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       protect_your_uploads
 * Domain Path:       /languages 
 */ 

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { 
	die;
}

if ( ! function_exists( 'pyu_fs' ) ) {
  // Create a helper function for easy SDK access.
  function pyu_fs() {
      global $pyu_fs;

      if ( ! isset( $pyu_fs ) ) {
          // Include Freemius SDK.
          require_once dirname(__FILE__) . '/freemius/start.php';

          $pyu_fs = fs_dynamic_init( array(
              'id'                  => '6232',
              'slug'                => 'protect-uploads-with-login-page',
              'type'                => 'plugin',
              'public_key'          => 'pk_7b21e9174ad7be780e2cc5f9cb283',
              'is_premium'          => false,
              'has_addons'          => false,
              'has_paid_plans'      => false,
              'menu'                => array(
                  'slug'           => 'protect-your-uploads-menu',
                  'account'        => false,
                  'contact'        => false,
                  'support'        => false,
              ),
          ) );
      }

      return $pyu_fs;
  }

  // Init Freemius.
  pyu_fs();
  // Signal that SDK was initiated.
  do_action( 'pyu_fs_loaded' );
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PYU_PROTECT_YOUR_UPLOADS_VERSION', '1.2' );

define('PYU_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PYU_PLUGIN_ADMIN_DIR', plugin_dir_path(__FILE__) . 'admin/');
define('PYU_PLUGIN_PUBLIC_DIR', plugin_dir_path(__FILE__) . 'public/');

// require config file
require_once(PYU_PLUGIN_DIR . 'includes/class-pyu-config.php');


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pyu-activator.php
 */
function activate_protect_your_uploads() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pyu-activator.php';
	Protect_Your_Uploads_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_protect_your_uploads() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pyu-deactivator.php';
	Protect_Your_Uploads_Deactivator::deactivate();
} 	 

register_activation_hook( __FILE__, 'activate_protect_your_uploads' );
// register_deactivation_hook( __FILE__, 'deactivate_protect_your_uploads' );
  
/** 
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pyu.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 * 
 * @since    1.0.0 
 */ 
function protect_your_uploads_run_plugin() {
 
	$plugin = new Protect_Your_Uploads();
	$plugin->run();
	register_deactivation_hook( __FILE__, array($plugin, 'deactivate_plugin'));
 
}

protect_your_uploads_run_plugin();
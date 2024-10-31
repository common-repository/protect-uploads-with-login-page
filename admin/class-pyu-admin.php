<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    protect_your_uploads
 * @subpackage protect_your_uploads/admin
 * @author     Your Name <email@example.com>
 */
class Protect_Your_Uploads_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $cpt_user;
	public $custom_rewrite;
	public $pyu_ajax;
	public $pyu_handle_file_request;
	public $uploads_controller;
  public $settings_controller;
  public $directory_listing;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->load_dependencies();

		$this->cpt_user = new PYU_CPT_User();
		$this->custom_rewrite = new PYU_RewriteRules();
		$this->uploads_controller = new PYU_Uploads_Controller();
    $this->settings_controller = new PYU_Settings_Controller();
    $this->directory_listing = new PYU_Directory_Listing();
	}

	private function load_dependencies()
	{
		require_once(PYU_PLUGIN_ADMIN_DIR . 'class-pyu-cpt-user.php');
		require_once(PYU_PLUGIN_ADMIN_DIR . 'class-pyu-rewrite-rules.php');
		require_once(PYU_PLUGIN_ADMIN_DIR . 'class-pyu-handle-request.php');
		require_once(PYU_PLUGIN_ADMIN_DIR . 'class-pyu-upload-controller.php');
    require_once(PYU_PLUGIN_ADMIN_DIR . 'class-pyu-settings-controller.php');
    require_once(PYU_PLUGIN_ADMIN_DIR . 'class-pyu-directory-listing.php');
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/protect_your_uploads_admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name_admin.js', array( 'jquery' ), $this->version, false );

	}

	public function display_settings()
	{
		include(PYU_PLUGIN_ADMIN_DIR . 'partials/settings/settings.php');
	}					

	public function display_start_page() {
		include(PYU_PLUGIN_ADMIN_DIR . 'partials/faq/faq.php');
	}


	public function add_plugin_menu_pages()
	{
		// add_menu_page( $page_title:string, $menu_title:string, $capability:string, $menu_slug:string, $function:callable, $icon_url:string, $position:integer|null )
		add_menu_page(
			__('Protect Your Uploads', 'protect_your_uploads'),
			__('Protect Your Uploads', 'protect_your_uploads'),
			'manage_options',
			'protect-your-uploads-menu',
			array($this, 'display_start_page'),
			'dashicons-lock'
		);

		add_submenu_page(
			'protect-your-uploads-menu',
			__('Files Overview', 'protect_your_uploads'),
			__('Files Overview', 'protect_your_uploads'),
			'manage_options',
			'protect-your-uploads-files',
			array($this->uploads_controller, 'display_file_dashboard')
		);

		add_submenu_page(
			'protect-your-uploads-menu',
			__('Settings', 'protect_your_uploads'),
			__('Settings', 'protect_your_uploads'),
			'manage_options',
			'protect-your-uploads-settings',
			array($this, 'display_settings')
		);

	}
}

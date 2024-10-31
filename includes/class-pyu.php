<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    protect_your_uploads
 * @subpackage protect_your_uploads/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    protect_your_uploads
 * @subpackage protect_your_uploads/includes
 * @author     Your Name <email@example.com>
 */
class Protect_Your_Uploads {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Protect_Your_Uploads_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $plugin_admin;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'PROTECT_YOUR_UPLOADS_VERSION' ) ) {
			$this->version = PROTECT_YOUR_UPLOADS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'protect_your_uploads';

		$this->load_dependencies();

		$this->plugin_admin = new Protect_Your_Uploads_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pyu-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pyu-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pyu-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-pyu-public.php';

		$this->loader = new Protect_Your_Uploads_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Protect_Your_Uploads_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		

		$this->loader->add_action('admin_enqueue_scripts', $this->plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $this->plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('admin_menu', $this->plugin_admin, 'add_plugin_menu_pages');

		// Custom Post Type
		$this->loader->add_action('init', $this->plugin_admin->cpt_user, 'register_type');
		$this->loader->add_action('save_post', $this->plugin_admin->cpt_user, 'save_meta_data');
		$this->loader->add_action('admin_menu', $this->plugin_admin->cpt_user, 'users_submenu_page');
		$this->loader->add_filter('manage_edit-pyu-user_columns', $this->plugin_admin->cpt_user, 'custom_edit_columns');
		$this->loader->add_action('manage_pyu-user_posts_custom_column', $this->plugin_admin->cpt_user, 'fill_custom_edit_columns', 10, 2);
		$this->loader->add_filter('list_table_primary_column', $this->plugin_admin->cpt_user, 'change_primary_edit_page_column', 10, 2);
		$this->loader->add_action('admin_enqueue_scripts', $this->plugin_admin->cpt_user, 'enqueue_scripts');
		$this->loader->add_filter('post_row_actions', $this->plugin_admin->cpt_user, 'modify_list_row_actions', 10, 2);


		// Rewrite Rules
		$this->loader->add_filter('mod_rewrite_rules', $this->plugin_admin->custom_rewrite, 'add_htaccess_content');
		$this->loader->add_filter('query_vars', $this->plugin_admin->custom_rewrite, 'add_query_variables');
		$this->loader->add_action('admin_init', $this->plugin_admin->custom_rewrite, 'check_flush');


		$this->loader->add_action('admin_enqueue_scripts', $this->plugin_admin->uploads_controller, 'admin_enqueue_scripts');
		$this->loader->add_action('wp_ajax_pyu-protect-files', $this->plugin_admin->uploads_controller, 'handle_ajax_request_dashboard_save');
    $this->loader->add_action('parse_query', $this->plugin_admin->uploads_controller, 'handle_file_request');
    $this->loader->add_action('wp_enqueue_scripts', $this->plugin_admin->uploads_controller->request_handler, 'enqueue_styles', 9999);
    $this->loader->add_filter('template_include', $this->plugin_admin->uploads_controller, 'select_template');
    $this->loader->add_filter('pre_get_document_title', $this->plugin_admin->uploads_controller->request_handler, 'set_title', 9999);
    $this->loader->add_action('delete_post', $this->plugin_admin->uploads_controller, 'delete_custom_user');
    $this->loader->add_action('wp_trash_post', $this->plugin_admin->uploads_controller, 'delete_custom_user');
    

		$this->loader->add_action('admin_init', $this->plugin_admin->settings_controller, 'register_settings');
    
    $this->loader->add_action('admin_init', $this->plugin_admin->directory_listing, 'init');

	}

	public function deactivate_plugin() {
		remove_filter('mod_rewrite_rules', array($this->plugin_admin->custom_rewrite, 'add_htaccess_content'));
		flush_rewrite_rules();
	}
 
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Protect_Your_Uploads_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

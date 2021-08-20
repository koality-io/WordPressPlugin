<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.koality.io
 * @since      1.0.0
 *
 * @package    Koality
 * @subpackage Koality/includes
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
 * @package    Koality
 * @subpackage Koality/includes
 * @author     Nils Langner <Nils.langner@leankoala.com>
 */
class Koality {

    const OPTION_API_KEY = 'koality_api_key';

    const CONFIG_DATA_PROTECTION_KEY = 'koality_data_protection';
    const CONFIG_DATA_PROTECTION_VALUE = 0;

    const CONFIG_SYSTEM_SPACE_KEY = 'koality_system_space';
    const CONFIG_SYSTEM_SPACE_VALUE = 95;

    const CONFIG_SYSTEM_PLUGINS_OUTDATED_KEY = 'koality_system_plugins_outdated';
    const CONFIG_SYSTEM_PLUGINS_OUTDATED_VALUE = 0;

    const CONFIG_WOOCOMMERCE_ORDER_PEAK_KEY = 'koality_woocommerce_order_per_hour_peak';
    const CONFIG_WOOCOMMERCE_ORDER_PEAK_VALUE = 0;

    const CONFIG_WOOCOMMERCE_PRODUCT_COUNT_KEY = 'koality_woocommerce_product_count';
    const CONFIG_WOOCOMMERCE_PRODUCT_COUNT_VALUE = 0;

    const CONFIG_WORDPRESS_LOGFILE_ERROR_COUNT_KEY = 'koality_wordpress_logfile_error_count';
    const CONFIG_WORDPRESS_LOGFILE_ERROR_COUNT_VALUE = 0;

    const CONFIG_WORDPRESS_INSECURE_OUTDATED_KEY = 'koality_wordpress_system_insecure_outdated';
    const CONFIG_WORDPRESS_INSECURE_OUTDATED_VALUE = 0;

    const CONFIG_WORDPRESS_ADMIN_COUNT_KEY = 'koality_wordpress_system_admin_count';
    const CONFIG_WORDPRESS_ADMIN_COUNT_VALUE = 0;

    const CONFIG_WORDPRESS_PLUGINS_OUTDATED_KEY = 'koality_wordpress_plugins_outdated';
    const CONFIG_WORDPRESS_PLUGINS_OUTDATED_VALUE = 0;

    const CONFIG_WOOCOMMERCE_ORDER_PEAK_OFF_KEY = 'koality_woocommerce_order_per_hour_off_peak';
    const CONFIG_WOOCOMMERCE_ORDER_PEAK_OFF_VALUE = 0;

    const CONFIG_WOOCOMMERCE_RUSH_HOUR_START_KEY = 'koality_woocommerce_rush_hour_start';
    const CONFIG_WOOCOMMERCE_RUSH_HOUR_START_VALUE = 9;

    const CONFIG_WOOCOMMERCE_RUSH_HOUR_END_KEY = 'koality_woocommerce_rush_hour_end';
    const CONFIG_WOOCOMMERCE_RUSH_HOUR_END_VALUE = 17;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Koality_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'KOALITY_VERSION' ) ) {
			$this->version = KOALITY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'koality';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Koality_Loader. Orchestrates the hooks of the plugin.
	 * - Koality_i18n. Defines internationalization functionality.
	 * - Koality_Admin. Defines all hooks for the admin area.
	 * - Koality_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-koality-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-koality-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-koality-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-koality-public.php';

		$this->loader = new Koality_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Koality_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Koality_i18n();

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

		$plugin_admin = new Koality_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'menu_enrich');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Koality_Public( $this->get_plugin_name(), $this->get_version() );

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
	 * @return    Koality_Loader    Orchestrates the hooks of the plugin.
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

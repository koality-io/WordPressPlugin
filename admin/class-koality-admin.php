<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.koality.io
 * @since      1.0.0
 *
 * @package    Koality
 * @subpackage Koality/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Koality
 * @subpackage Koality/admin
 * @author     Nils Langner <Nils.langner@leankoala.com>
 */
class Koality_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Koality_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Koality_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/koality-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Koality_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Koality_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/koality-admin.js', array('jquery'), $this->version, false);

    }

    public function menu_enrich()
    {
        //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        add_menu_page($this->plugin_name, 'koality.io', 'administrator', $this->plugin_name, array($this, 'displayPluginAdminDashboard'), 'dashicons-chart-area', 26);

        //add_submenu_page( '$parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
        add_submenu_page($this->plugin_name, 'Plugin Name Settings', 'Settings', 'administrator', $this->plugin_name . '-settings', array($this, 'displayKoalitySettings'));
    }

    public function displayKoalitySettings()
    {
        $partialName = 'partials/'.$this->plugin_name.'-settings-display.php';
        require_once $partialName;
    }
}

<?php

namespace Koality\WordPressPlugin\Admin;

use Koality\WordPressPlugin\Checks\WordPressCheckContainer;
use Koality\WordPressPlugin\Koality;
use Koality\WordPressPlugin\WordPress\Options;

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
class Admin
{
    const ENABLED_KEY = 'koality_enabled';

    private $knownSections = [];

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

        add_action('admin_init', array($this, 'registerAndBuildFields'));

        // add_action('wp_dashboard_setup', array($this, 'initDashboardWidgets'));
    }

    function initDashboardWidgets()
    {
        global $wp_meta_boxes;
        wp_add_dashboard_widget('custom_help_widget', 'koality.io', array($this, 'custom_dashboard_help'));
    }

    function custom_dashboard_help()
    {
        require_once 'partials/widget.php';
    }

    public function menu_enrich()
    {
        add_menu_page($this->plugin_name, 'koality.io', 'administrator', $this->plugin_name, array($this, 'displayPluginAdminDashboard'), 'dashicons-chart-area', 26);

        add_submenu_page($this->plugin_name, 'Configuration', 'Configuration', 'administrator', 'checks', array($this, 'displayChecks'));

        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            add_submenu_page($this->plugin_name, 'WooCommerce Monitoring', 'WooCommerce Monitoring', 'administrator', $this->plugin_name . '-settings-woocommerce', array($this, 'displayWooCommerceSettings'));
        }

        add_submenu_page($this->plugin_name, 'System Monitoring', 'System Monitoring', 'administrator', $this->plugin_name . '-settings-server', array($this, 'displayServerSettings'));
        add_submenu_page($this->plugin_name, 'Security Monitoring', 'Security Monitoring', 'administrator', $this->plugin_name . '-settings-security', array($this, 'displaySecuritySettings'));
        add_submenu_page($this->plugin_name, 'Content Monitoring', 'Content Monitoring', 'administrator', $this->plugin_name . '-settings-content', array($this, 'displayContentSettings'));

    }

    public function displayServerSettings()
    {
        require_once 'partials/settings-server.php';
    }

    public function displayChecks()
    {
        $stored = false;

        if (isset($_POST) && array_key_exists(self::ENABLED_KEY, $_POST)) {
            Options::set(self::ENABLED_KEY, $_POST[self::ENABLED_KEY]);
            $stored = true;
        }

        $enabledChecks = Options::get(self::ENABLED_KEY);

        if (!$enabledChecks) {
            $enabledChecks = [];
        }

        $checkContainer = Koality::getWordPressChecks();

        $checks = [];

        foreach ($checkContainer->getChecks() as $check) {
            $checks[$check->getGroupAsString()][] = $check;
        }

        require_once 'partials/checks.php';
    }

    public function displayWooCommerceSettings()
    {
        require_once 'partials/settings-woocommerce.php';
    }

    public function displaySecuritySettings()
    {
        require_once 'partials/settings-security.php';
    }

    public function displayContentSettings()
    {
        require_once 'partials/settings-content.php';
    }

    public function displayPluginAdminDashboard()
    {
        require_once 'partials/general.php';
    }

    public function registerAndBuildFields()
    {
        $checkContainer = Koality::getWordPressChecks();
        $this->addSettings($checkContainer);
    }

    private function addSection($identifier, $page, $title = '', $description = '')
    {
        if (!array_key_exists($page, $this->knownSections) || !array_key_exists($identifier, $this->knownSections[$page])) {
            add_settings_section(
            // ID used to identify this section and with which to register options
                $identifier,
                // Title to be displayed on the administration page
                $title,
                // Callback used to render the description of the section
                function () use ($description) {
                    echo esc_attr($description);
                },
                // Page on which to add this section of options
                $page
            );

            $this->knownSections[$page][$identifier] = true;
        }
    }

    private function addSettings(WordPressCheckContainer $checkContainer)
    {
        // Sections with description
        $this->addSection('koality_rush_hour_section', 'koality_woocommerce_settings', 'Peak sales handling', 'The koality.io WordPress plugin is able to monitor WooCommerce business metrics. It distinguishes between peak sales times and off-peak sales times.');
        $this->addSection('koality_general_section', 'koality_woocommerce_settings', 'Business metrics');
        $this->addSection('koality_general_section', 'koality_general_settings', 'Data protection', 'If the data protection mode is activated this plugin does not send detailed business information like orders per hour. It will only send the information that the check succeeded.');
        $this->addSection('koality_security_section', 'koality_security_settings', 'Security Settings', 'System settings take care of the WooCommerce and the WordPress system.');
        // $this->addSection('koality_server_logfile_section', 'koality_server_settings', 'Log file analysis', 'System settings take care of the WooCommerce and the WordPress system.');

        // General settings
        $this->addSetting('koality_general_settings', 'koality_general_section', Koality::CONFIG_DATA_PROTECTION_KEY, 'Hide detailed data', 'false', ['subtype' => 'checkbox']);

        // WooCommerce settings
        $this->addSetting('koality_woocommerce_settings', 'koality_rush_hour_section', Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_START_KEY, 'Peak sales start (24h)', 'false', ['min' => 0, 'max' => 24]);
        $this->addSetting('koality_woocommerce_settings', 'koality_rush_hour_section', Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_END_KEY, 'Peak sales end (24h)', 'false', ['min' => 0, 'max' => 24]);
        $this->addSetting('koality_woocommerce_settings', 'koality_general_section', Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_KEY, 'Minimum orders per hour (peak sales)', 'false', ['min' => 0]);
        $this->addSetting('koality_woocommerce_settings', 'koality_general_section', Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_OFF_KEY, 'Minimum orders per hour (off-peak sales)', 'false', ['min' => 0]);
        // $this->addSetting('koality_woocommerce_settings', 'koality_general_section', Koality::CONFIG_WOOCOMMERCE_PRODUCT_COUNT_KEY, 'Minimum number of products', 'false', ['min' => 0]);

        foreach ($checkContainer->getSettings() as $setting) {
            $this->addSetting($setting['page'], $setting['section'], $setting['identifier'], $setting['label'], $setting['required'], $setting['args']);
        }
    }

    private function addSetting($page, $section, $identifier, $label, $required = 'true', $args = [])
    {
        $this->addSection($section, $page);

        $defaultArgs = [
            'type' => 'input',
            'subtype' => 'number',
            'id' => $identifier,
            'name' => $identifier,
            'required' => $required,
            'get_options_list' => '',
            'value_type' => 'normal',
            'wp_data' => 'option'
        ];

        $fullArgs = array_merge($defaultArgs, $args);

        add_settings_field(
            $identifier,
            $label,
            array($this, 'koality_render_settings_field'),
            $page,
            $section,
            $fullArgs
        );

        register_setting(
            $page,
            $identifier
        );
    }

    public function koality_render_settings_field($args)
    {
        if ($args['wp_data'] == 'option') {
            $wp_data_value = get_option($args['name']);
        } elseif ($args['wp_data'] == 'post_meta') {
            $wp_data_value = get_post_meta($args['post_id'], $args['name'], true);
        }

        switch ($args['type']) {

            case 'input':
                $value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
                if ($args['subtype'] != 'checkbox') {
                    $prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">' . esc_attr($args['prepend_value']) . '</span>' : '';
                    $prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';
                    $step = (isset($args['step'])) ? 'step="' . $args['step'] . '"' : '';
                    $min = (isset($args['min'])) ? 'min="' . (int)$args['min'] . '"' : '';
                    $max = (isset($args['max'])) ? 'max="' . (int)$args['max'] . '"' : '';

                    if (isset($args['disabled'])) {
                        // hide the actual input bc if it was just a disabled input the information saved in the database would be wrong - bc it would pass empty values and wipe the actual information
                        echo $prependStart . '<input type="' . esc_attr($args['subtype']) . '" id="' . esc_attr($args['id']) . '_disabled" ' . esc_attr($step) . ' ' . $max . ' ' . $min . ' name="' . esc_attr($args['name']) . '_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="' . esc_attr($args['id']) . '" ' . esc_attr($step) . ' ' . esc_attr($max) . ' ' . esc_attr($min) . ' name="' . esc_attr($args['name']) . '" size="40" value="' . esc_attr($value) . '" />' . $prependEnd;
                    } else {
                        echo $prependStart . '<input type="' . esc_attr($args['subtype']) . '" id="' . esc_attr($args['id']) . '" "' . esc_attr($args['required']) . '" ' . esc_attr($step) . ' ' . $max . ' ' . $min . ' name="' . esc_attr($args['name']) . '" size="40" value="' . esc_attr($value) . '" />' . $prependEnd;
                    }

                } else {
                    $checked = ($value) ? 'checked' : '';
                    echo '<input type="' . esc_attr($args['subtype']) . '" id="' . esc_attr($args['id']) . '" "' . esc_attr($args['required']) . '" name="' . esc_attr($args['name']) . '" size="40" value="1" ' . esc_attr($checked) . ' />';
                }
                break;
            default:
                # code...
                break;
        }
    }
}

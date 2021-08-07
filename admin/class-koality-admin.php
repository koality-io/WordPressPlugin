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
        add_menu_page($this->plugin_name, 'koality.io', 'administrator', $this->plugin_name, array($this, 'displayPluginAdminDashboard'), 'dashicons-chart-area', 26);
        add_submenu_page($this->plugin_name, 'System Monitoring', 'System Monitoring', 'administrator', $this->plugin_name . '-settings-server', array($this, 'displayServerSettings'));

        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            add_submenu_page($this->plugin_name, 'WooCommerce Monitoring', 'WooCommerce Monitoring', 'administrator', $this->plugin_name . '-settings-woocommerce', array($this, 'displayWooCommerceSettings'));
        }
    }

    public function displayServerSettings()
    {
        $partialName = 'partials/' . $this->plugin_name . '-settings-server-display.php';
        require_once $partialName;
    }

    public function displayWooCommerceSettings()
    {
        $partialName = 'partials/' . $this->plugin_name . '-settings-woocommerce-display.php';
        require_once $partialName;
    }

    public function displayPluginAdminDashboard()
    {
        $partialName = 'partials/' . $this->plugin_name . '-admin-display.php';
        require_once $partialName;
    }

    public function registerAndBuildFields()
    {
        $this->addSettings();
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
                    echo $description;
                },
                // Page on which to add this section of options
                $page
            );

            $this->knownSections[$page][$identifier] = true;
        }
    }

    private function addSettings()
    {
        // Sections with description
        $this->addSection('koality_rush_hour_section', 'koality_woocommerce_settings', 'Peak sales handling', 'The koality.io WordPress plugin is able to monitor WooCommerce business metrics. It distinguishes between peak sales times and off-peak sales times.');
        $this->addSection('koality_general_section', 'koality_woocommerce_settings', 'Business metrics');
        $this->addSection('koality_general_section', 'koality_general_settings', 'Data protection', 'If the data protection mode is activated this plugin does not send detailed business information like orders per hour. It will only send the information that the check succeeded.');

        // General settings
        $this->addSetting('koality_general_settings', 'koality_general_section', Koality::CONFIG_DATA_PROTECTION_KEY, 'Hide detailed data', 'false', ['subtype' => 'checkbox']);

        // Server settings
        $this->addSetting('koality_server_settings', 'koality_general_section', Koality::CONFIG_SYSTEM_PLUGINS_OUTDATED_KEY, 'Maximum number of outdated plugins', 'false', ['min' => 0]);
        $this->addSetting('koality_server_settings', 'koality_general_section', Koality::CONFIG_SYSTEM_SPACE_KEY, 'Maximum space usage (%)', 'false', ['min' => 0, 'max' => 100]);

        // WooCommerce settings
        $this->addSetting('koality_woocommerce_settings', 'koality_rush_hour_section', Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_START_KEY, 'Peak sales start (24h)', 'false', ['min' => 0, 'max' => 24]);
        $this->addSetting('koality_woocommerce_settings', 'koality_rush_hour_section', Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_END_KEY, 'Peak sales end (24h)', 'false', ['min' => 0, 'max' => 24]);
        $this->addSetting('koality_woocommerce_settings', 'koality_general_section', Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_KEY, 'Minimum orders per hour (peak sales)', 'false', ['min' => 0]);
        $this->addSetting('koality_woocommerce_settings', 'koality_general_section', Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_OFF_KEY, 'Minimum orders per hour (off-peak sales)', 'false', ['min' => 0]);

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
                    $prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">' . $args['prepend_value'] . '</span>' : '';
                    $prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';
                    $step = (isset($args['step'])) ? 'step="' . $args['step'] . '"' : '';
                    $min = (isset($args['min'])) ? 'min="' . $args['min'] . '"' : '';
                    $max = (isset($args['max'])) ? 'max="' . $args['max'] . '"' : '';
                    if (isset($args['disabled'])) {
                        // hide the actual input bc if it was just a disabled input the informaiton saved in the database would be wrong - bc it would pass empty values and wipe the actual information
                        echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '_disabled" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="' . $args['id'] . '" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="40" value="' . esc_attr($value) . '" />' . $prependEnd;
                    } else {
                        echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '" "' . $args['required'] . '" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="40" value="' . esc_attr($value) . '" />' . $prependEnd;
                    }
                    /*<input required="required" '.$disabled.' type="number" step="any" id="'.$this->plugin_name.'_cost2" name="'.$this->plugin_name.'_cost2" value="' . esc_attr( $cost ) . '" size="25" /><input type="hidden" id="'.$this->plugin_name.'_cost" step="any" name="'.$this->plugin_name.'_cost" value="' . esc_attr( $cost ) . '" />*/

                } else {
                    $checked = ($value) ? 'checked' : '';
                    echo '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '" "' . $args['required'] . '" name="' . $args['name'] . '" size="40" value="1" ' . $checked . ' />';
                }
                break;
            default:
                # code...
                break;
        }
    }
}

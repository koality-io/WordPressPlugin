<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.koality.io
 * @since      1.0.0
 *
 * @package    Koality
 * @subpackage Koality/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Koality
 * @subpackage Koality/includes
 * @author     Nils Langner <Nils.langner@leankoala.com>
 */
class Koality_Activator
{
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        if (!get_option(Koality::OPTION_API_KEY)) {
            add_option(Koality::OPTION_API_KEY, self::createGuid(), '', 'no');

            // Global
            add_option(Koality::CONFIG_DATA_PROTECTION_KEY, Koality::CONFIG_DATA_PROTECTION_VALUE, '', 'no');

            // Server
            add_option(Koality::CONFIG_SYSTEM_SPACE_KEY, Koality::CONFIG_SYSTEM_SPACE_VALUE, '', 'no');

            // WooCommerce
            add_option(Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_START_KEY, Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_START_VALUE, '', 'no');
            add_option(Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_END_KEY, Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_END_VALUE, '', 'no');
            add_option(Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_KEY, Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_VALUE, '', 'no');
            add_option(Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_OFF_KEY, Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_OFF_VALUE, '', 'no');
            add_option(Koality::CONFIG_WOOCOMMERCE_PRODUCT_COUNT_KEY, Koality::CONFIG_WOOCOMMERCE_PRODUCT_COUNT_VALUE, '', 'no');

            // WordPress
            add_option(Koality::CONFIG_WORDPRESS_INSECURE_OUTDATED_KEY, Koality::CONFIG_WORDPRESS_INSECURE_OUTDATED_VALUE, '', 'no');
            add_option(Koality::CONFIG_SYSTEM_PLUGINS_OUTDATED_KEY, Koality::CONFIG_SYSTEM_PLUGINS_OUTDATED_VALUE, '', 'no');
            add_option(Koality::CONFIG_WORDPRESS_PLUGINS_OUTDATED_KEY, Koality::CONFIG_WORDPRESS_PLUGINS_OUTDATED_VALUE, '', 'no');
        } else {
            update_option(Koality::OPTION_API_KEY, self::createGuid(), '', 'no');
        }
    }

    private static function createGuid()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
}

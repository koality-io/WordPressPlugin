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
        self::addSetting(Koality::OPTION_API_KEY, self::createGuid());
        self::addSetting(Koality::CONFIG_DATA_PROTECTION_KEY, Koality::CONFIG_DATA_PROTECTION_VALUE);
        self::addSetting(Koality::CONFIG_SYSTEM_SPACE_KEY, Koality::CONFIG_SYSTEM_SPACE_VALUE);

        self::addSetting(Koality::CONFIG_DATA_PROTECTION_KEY, Koality::CONFIG_DATA_PROTECTION_VALUE);

        // Server
        self::addSetting(Koality::CONFIG_SYSTEM_SPACE_KEY, Koality::CONFIG_SYSTEM_SPACE_VALUE);

        // WooCommerce
        self::addSetting(Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_START_KEY, Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_START_VALUE);
        self::addSetting(Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_END_KEY, Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_END_VALUE);
        self::addSetting(Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_KEY, Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_VALUE);
        self::addSetting(Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_OFF_KEY, Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_OFF_VALUE);
        self::addSetting(Koality::CONFIG_WOOCOMMERCE_PRODUCT_COUNT_KEY, Koality::CONFIG_WOOCOMMERCE_PRODUCT_COUNT_VALUE);

        // WordPress
        self::addSetting(Koality::CONFIG_WORDPRESS_INSECURE_OUTDATED_KEY, Koality::CONFIG_WORDPRESS_INSECURE_OUTDATED_VALUE);
        self::addSetting(Koality::CONFIG_SYSTEM_PLUGINS_OUTDATED_KEY, Koality::CONFIG_SYSTEM_PLUGINS_OUTDATED_VALUE);
        self::addSetting(Koality::CONFIG_WORDPRESS_PLUGINS_OUTDATED_KEY, Koality::CONFIG_WORDPRESS_PLUGINS_OUTDATED_VALUE);
        self::addSetting(Koality::CONFIG_WORDPRESS_ADMIN_COUNT_KEY, Koality::CONFIG_WORDPRESS_ADMIN_COUNT_VALUE);
    }

    private static function addSetting($key, $value)
    {
        if (!get_option($key)) {
            add_option($key, $value, '', 'no');
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

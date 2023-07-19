<?php

namespace Koality\WordPressPlugin\Basic;

use Koality\WordPressPlugin\Admin\Admin;
use Koality\WordPressPlugin\Checks\WordPressCheckContainer;
use Koality\WordPressPlugin\Koality;
use Koality\WordPressPlugin\WordPress\Options;

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
class Activator
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

        // WooCommerce
        self::addSetting(Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_START_KEY, Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_START_VALUE);
        self::addSetting(Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_END_KEY, Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_END_VALUE);
        self::addSetting(Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_KEY, Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_VALUE);
        self::addSetting(Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_OFF_KEY, Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_OFF_VALUE);

        $container = Koality::getWordPressChecks();

        self::enableChecks($container);

        foreach ($container->getChecks() as $check) {
            self::addSetting($check->getConfigKey(), $check->getConfigDefaultValue());
        }
    }

    static private function enableChecks(WordPressCheckContainer $container)
    {
        $enabledChecks = Options::get(Admin::ENABLED_KEY);

        if (is_bool($enabledChecks)) {
            return;
        }

        foreach ($container->getChecks() as $check) {
            if (!array_key_exists($check->getIdentifier(), $enabledChecks)) {
                if ($check->isEnabledByDefault()) {
                    $enabledChecks[$check->getIdentifier()] = 'on';
                }
            }
        }

        Options::set(Admin::ENABLED_KEY, $enabledChecks);
    }

    private static function addSetting($key, $value)
    {
        if (!get_option($key)) {
            add_option($key, $value, '', 'no');
        } else {
            if ($key == Koality::OPTION_API_KEY) {
                update_option($key, $value, '', 'no');
            }
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

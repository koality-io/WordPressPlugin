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
            add_option(Koality::CONFIG_SERVER_SPACE_KEY, Koality::CONFIG_SERVER_SPACE_VALUE, '', 'no');
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

<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.koality.io
 * @since      1.0.0
 *
 * @package    Koality
 * @subpackage Koality/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Koality
 * @subpackage Koality/includes
 * @author     Nils Langner <Nils.langner@leankoala.com>
 */
class Koality_Deactivator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate()
    {
        if (!get_option(Koality::OPTION_API_KEY)) {
            add_option(Koality::OPTION_API_KEY, 'off', '', 'no');
        } else {
            update_option(Koality::OPTION_API_KEY, 'off', '', 'no');
        }
    }

}

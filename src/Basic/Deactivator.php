<?php

namespace Koality\WordPressPlugin\Basic;

use Koality\WordPressPlugin\Koality;

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
class Deactivator
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

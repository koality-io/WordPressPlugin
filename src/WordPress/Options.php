<?php


namespace Koality\WordPressPlugin\WordPress;

/**
 * Class Options
 *
 * @package Koality\WordPressPlugin\WordPress
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-09-12
 */
abstract class Options
{
    static public function get($key)
    {
        return get_option($key);
    }
}

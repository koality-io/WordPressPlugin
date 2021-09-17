<?php

namespace Koality\WordPressPlugin\WordPress;

if (!defined('WP_KOALITY_IO')) {
    exit;
}

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

    static public function set($key, $value)
    {
        if (self::get($key)) {
            update_option($key, $value);
        } else {
            add_option($key, $value);
        }
    }
}

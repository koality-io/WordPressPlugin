<?php


namespace Koality\WordPressPlugin\WordPress\Repository;

/**
 * Class PluginRepository
 *
 * @package Koality\WordPressPlugin\WordPress\Repository
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-09-12
 */
abstract class PluginRepository
{
    static public function find($identifier)
    {
        $plugins =
    }

    static public function getPlugins()
    {
        return get_plugins();
    }
}

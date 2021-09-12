<?php

namespace Koality\WordPressPlugin\WordPress\Repository;

if (!defined('WP_KOALITY_IO')) {
    exit;
}

use Koality\WordPressPlugin\WordPress\Entity\Plugin;

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
    /**
     * Return a plugin object.
     *
     * @param string $identifier
     *
     * @return Plugin
     */
    static public function find($identifier)
    {
        if (!self::pluginExists($identifier)) {
            throw new \RuntimeException('No plugin with identifier "' . $identifier . '" found.');
        }

        return new Plugin($identifier);
    }

    /**
     * Return all updatable plugins.
     *
     * @return array
     */
    static public function findUpdatable()
    {
        return get_plugin_updates();
    }

    /**
     * Return true if a plugin with the given identifier exists.
     *
     * @param string $identifier
     * @return bool
     */
    static public function pluginExists($identifier)
    {
        $plugins = \get_plugins();
        return array_key_exists($identifier, $plugins);
    }

    /**
     * Return a list of plugins.
     *
     * @return array
     */
    static public function getPlugins()
    {
        return get_plugins();
    }
}

<?php


namespace Koality\WordPressPlugin\WordPress\Entity;

use Koality\WordPressPlugin\WordPress\Exception\WordPressException;

if (!defined('WP_KOALITY_IO')) {
    exit;
}

include_once ABSPATH . 'wp-admin/includes/plugin.php';
include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

/**
 * Class Plugin
 *
 * @package Koality\WordPressPlugin\WordPress\Entity
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-09-12
 */
class Plugin
{
    private $identifier;

    /**
     * @param string $identifier
     */
    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Activate the plugin.
     *
     * @param bool $silent
     *
     * @throws WordPressException
     */
    public function activate($silent = true)
    {
        if ($this->isActive()) {
            return;
        }

        $result = activate_plugin($this->identifier, '', $this->isActiveForNetwork($this->identifier), $silent);

        if (is_wp_error($result)) {
            $e = new WordPressException('activation_fail');
            $e->setWpError($result);
            throw $e;
        }
    }

    /**
     * Update the given plugin.
     *
     * @throws WordPressException
     */
    public function update()
    {
        $plugin = $this->identifier;

        $nonce = 'upgrade-plugin_' . $plugin;
        $url = 'update.php?action=upgrade-plugin&plugin=' . urlencode($plugin);

        $isActive = $this->isActive();

        $pluginUpgrader = new \Plugin_Upgrader(new \Automatic_Upgrader_Skin(compact('nonce', 'url', 'plugin')));
        $response = $pluginUpgrader->upgrade($plugin);

        if ($response instanceof \WP_Error) {
            $e = new WordPressException($response->get_error_messages());
            $e->setWpError($response);
            throw  $e;
        }

        if($isActive) {
            $this->activate();
        }

        wp_update_plugins();
    }

    /**
     * Return true if the plugin is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return is_plugin_active($this->identifier);
    }

    /**
     * Return true if the plugin is active for the network.
     *
     * @return bool
     */
    public function isActiveForNetwork()
    {
        return is_plugin_active_for_network($this->identifier);
    }
}

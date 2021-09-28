<?php

namespace Koality\WordPressPlugin\Rest\Actions\Plugins;

use Koality\WordPressPlugin\Rest\Actions\BaseAction;
use Koality\WordPressPlugin\WordPress\Entity\Plugin;
use Koality\WordPressPlugin\WordPress\Repository\PluginRepository;

if (!defined('WP_KOALITY_IO')) {
    exit;
}

/**
 * Class UpdatePluginAction
 *
 * @package Koality\WordPressPlugin\Rest\Actions\Plugins
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-09-12
 */
class UpdatePluginAction extends BaseAction
{
    const ARGUMENT_PLUGIN = 'plugin';

    protected $routePath = 'plugin/update';

    protected $routeArguments = [
        self::ARGUMENT_PLUGIN => [
            'required' => true
        ]
    ];

    public function run(\WP_REST_Request $request)
    {
        $pluginIdentifier = $request->get_param(self::ARGUMENT_PLUGIN);

        if (!$pluginIdentifier) {
            return $this->returnFailure('No plugin with identifier "' . self::ARGUMENT_PLUGIN . '" found.', 'koality_plugin_not_found');
        }

        try {
            $plugin = PluginRepository::find($pluginIdentifier);
        } catch (\Exception $e) {
            return $this->returnFailure($e->getMessage());
        }

        $plugin->update();

        return $this->returnSuccess('Plugin successfully updated.', true, true);
    }

    public function getActionUrl(Plugin $plugin)
    {
        return $this->getActionBaseUrl() . '&' . self::ARGUMENT_PLUGIN . '=' . $plugin->getIdentifier();
    }
}

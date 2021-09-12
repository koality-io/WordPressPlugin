<?php

namespace Koality\WordPressPlugin\Rest\Actions\Plugins;

use Koality\WordPressPlugin\Rest\Actions\BaseAction;
use Koality\WordPressPlugin\WordPress\Repository\PluginRepository;

if (!defined('WP_KOALITY_IO')) {
    exit;
}

/**
 * Class UpdatePlugin
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
        $plugin = PluginRepository::find($pluginIdentifier);
        $plugin->update();

        return $this->returnSuccess('Plugin successfully updated.');
    }
}

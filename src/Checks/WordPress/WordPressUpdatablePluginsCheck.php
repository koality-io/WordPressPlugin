<?php

namespace Koality\WordPressPlugin\Checks\WordPress;

use Koality\WordPressPlugin\Checks\WordPressBasicCheck;
use Koality\WordPressPlugin\Checks\WordPressCheck;
use Koality\WordPressPlugin\Koality;
use Koality\WordPressPlugin\Rest\Actions\Plugins\UpdatePluginAction;
use Koality\WordPressPlugin\WordPress\Options;
use Koality\WordPressPlugin\WordPress\Repository\PluginRepository;
use Leankoala\HealthFoundationBase\Check\Action;
use Leankoala\HealthFoundationBase\Check\MetricAwareResult;
use Leankoala\HealthFoundationBase\Check\Result;

/**
 * Class WordPressCommentsPendingCheck
 *
 * Check if there are too many pending comments in the system.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-05
 */
class WordPressUpdatablePluginsCheck extends WordPressBasicCheck
{
    protected $configKey = 'koality_wordpress_plugins_outdated';
    protected $configDefaultValue = 0;

    protected $resultKey = Result::KOALITY_IDENTIFIER_PLUGINS_UPDATABLE;

    protected $group = WordPressCheck::GROUP_SECURITY;
    protected $description = 'Check if the number of plugins that need an update is greater than a defined threshold. In many cases WordPress blogs get hacked by the plugins and not WordPress itself.';
    protected $name = 'Number of updatable plugins';

    protected $settings = [
        [
            'label' => 'Maximum number of updatable plugins',
            'required' => true,
            'args' => ['min' => 0]
        ]
    ];

    /**
     * @inheritDoc
     */
    protected function doRun()
    {
        $updatablePlugins = PluginRepository::findUpdatable();

        $plugins = [];

        foreach ($updatablePlugins as $identifier => $plugin) {
            $plugins[$identifier] = $plugin->Name;
        }

        $limit = Options::get(Koality::CONFIG_WORDPRESS_PLUGINS_OUTDATED_KEY);

        if ($limit < count($plugins)) {
            $result = new MetricAwareResult(
                Result::STATUS_FAIL,
                'Too many plugins need an update.'
            );
        } else {
            $result = new MetricAwareResult(
                Result::STATUS_PASS,
                'Not too many plugins need an update.'
            );
        }

        $result->setLimitType(Result::LIMIT_TYPE_MAX);
        $result->setLimit($limit);
        $result->setMetric(count($plugins), 'plugins');
        $result->setObservedValuePrecision(0);

        $updateAction = new UpdatePluginAction();

        foreach ($plugins as $key => $pluginName) {
            $plugin = PluginRepository::find($key);
            $action = new Action('Update ' . $pluginName, $updateAction->getActionUrl($plugin), Action::TYPE_REST);
            $action->setGroup('koality_wordpress_plugin_update');
            $result->addAction($action);
        }

        return $result;
    }
}

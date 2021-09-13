<?php

namespace Koality\WordPressPlugin\Checks\WordPress;

use Koality\WordPressPlugin\Checks\WordPressBasicCheck;
use Koality\WordPressPlugin\Checks\WordPressCheck;
use Koality\WordPressPlugin\Koality;
use Koality\WordPressPlugin\WordPress\Options;
use Koality\WordPressPlugin\WordPress\Repository\PluginRepository;
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
    protected $description = '';

    protected $settings = [
        [
            'label' => 'Maximum number of updatable plugins',
            'required' => true
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

        foreach ($plugins as $key => $plugin) {
            $result->addArrayAttribute(Result::ATTRIBUTE_ACTION_URL, ['url' => 'url', 'label' => 'Update ' . $plugin]);
        }

        return $result;
    }
}

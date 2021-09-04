<?php

namespace Koality\WordPressPlugin\Checks\WordPress;

use Koality\WordPressPlugin\Koality;
use Leankoala\HealthFoundationBase\Check\Check;
use Leankoala\HealthFoundationBase\Check\MetricAwareResult;
use Leankoala\HealthFoundationBase\Check\Result;

/**
 * Class WordPressOrderCheck
 *
 * This check checks if there where enough orders within the last hour in the installed WooCommerce
 * shop.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-05
 */
class WordPressPlugins implements Check
{
    /**
     * @inheritDoc
     */
    public function run()
    {
        $updatablePlugins = get_plugin_updates();

        $plugins = [];

        foreach ($updatablePlugins as $plugin) {
            $plugins[] = $plugin->Name;
        }

        $limit = get_option(Koality::CONFIG_WORDPRESS_PLUGINS_OUTDATED_KEY);

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

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return 'WordPressPluginsUpdatable';
    }
}

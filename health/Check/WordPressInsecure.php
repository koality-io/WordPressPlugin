<?php

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\MetricAwareResult;
use Leankoala\HealthFoundation\Check\Result;

/**
 * Class WordPressInsecureCheck
 *
 * This check checks if the currently installed WordPress version is insecure.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-14
 */
class WordPressInsecure implements Check
{
    const API_ENDPOINT = 'https://api.wordpress.org/core/stable-check/1.0/';

    const STATUS_INSECURE = 'insecure';
    const STATUS_LATEST = 'latest';
    const STATUS_OUTDATED = 'outdated';

    /**
     * @inheritDoc
     */
    public function run()
    {
        $status = $this->getSystemStatus();

        $isOutdatedInsecure = (bool)get_option(Koality::CONFIG_WORDPRESS_INSECURE_OUTDATED_KEY);

        if ($isOutdatedInsecure) {
            if (in_array($status, [self::STATUS_INSECURE, self::STATUS_OUTDATED])) {
                $isFail = true;
            }else{
                $isFail = false;
            }
        }else{
            if (in_array($status, [self::STATUS_INSECURE])) {
                $isFail = true;
            }else{
                $isFail = false;
            }
        }

        if ($isFail) {
            $result = new MetricAwareResult(
                Result::STATUS_FAIL,
                'The WordPress version you are using is insecure (status: '.$status.')'
            );
        } else {
            $result = new MetricAwareResult(
                Result::STATUS_PASS,
                'The WordPress version you are using is secure (status: '.$status.')'
            );
        }

        $result->setLimitType(Result::LIMIT_TYPE_MIN);
        $result->setLimit(1);
        $result->setMetric($isFail ? 0 : 1, 'plugins', MetricAwareResult::METRIC_TYPE_PERCENT);
        $result->setObservedValuePrecision(0);

        return $result;
    }

    /**
     * Get the security status of the current WordPress installation.
     *
     * The method uses the official WordPress API for this check.
     *
     * @todo the API call should be cached for 1 hour.
     *
     * @return mixed
     */
    private function getSystemStatus()
    {
        global $wp_version;

        $versionsPlain = file_get_contents(self::API_ENDPOINT);

        $versions = json_decode($versionsPlain, true);

        if (!array_key_exists($wp_version, $versions)) {
            throw new \RuntimeException('The current WordPress version cannot be found the the security database.');
        }

        return $versions[$wp_version];
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return 'WordPressPluginsUpdatable';
    }
}

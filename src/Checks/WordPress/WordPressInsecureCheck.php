<?php

namespace Koality\WordPressPlugin\Checks\WordPress;

use Koality\WordPressPlugin\Checks\WordPressBasicCheck;
use Koality\WordPressPlugin\Checks\WordPressCheck;
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
class WordPressInsecureCheck extends WordPressBasicCheck
{
    const API_ENDPOINT = 'https://api.wordpress.org/core/stable-check/1.0/';

    const STATUS_INSECURE = 'insecure';
    const STATUS_LATEST = 'latest';
    const STATUS_OUTDATED = 'outdated';

    protected $configKey = 'koality_wordpress_system_insecure_outdated';
    protected $configDefaultValue = 0;

    protected $resultKey = Result::KOALITY_IDENTIFIER_SYSTEM_INSECURE;

    protected $group = WordPressCheck::GROUP_SECURITY;
    protected $description = 'Check if the current WordPress version is insecure. It is possible to decide if an outdated version is also considered as insecure.';
    protected $name = 'WordPress version insecure';

    protected $settings = [
        [
            'label' => 'Consider outdated WordPress versions as insecure',
            'required' => true,
            'args' => ['subtype' => 'checkbox']
        ]
    ];

    /**
     * @inheritDoc
     */
    protected function doRun()
    {
        $status = $this->getSystemStatus();

        $isOutdatedInsecure = (bool)$this->getLimit();

        if ($isOutdatedInsecure) {
            if (in_array($status, [self::STATUS_INSECURE, self::STATUS_OUTDATED])) {
                $isFail = true;
            } else {
                $isFail = false;
            }
        } else {
            if (in_array($status, [self::STATUS_INSECURE])) {
                $isFail = true;
            } else {
                $isFail = false;
            }
        }

        if ($isFail) {
            $result = new MetricAwareResult(
                Result::STATUS_FAIL,
                'The WordPress version you are using is insecure (status: ' . $status . ')'
            );
        } else {
            $result = new MetricAwareResult(
                Result::STATUS_PASS,
                'The WordPress version you are using is secure (status: ' . $status . ')'
            );
        }

        $result->setLimitType(Result::LIMIT_TYPE_MIN);
        $result->setLimit(1);
        $result->setMetric($isFail ? 0 : 1, 'updates', MetricAwareResult::METRIC_TYPE_PERCENT);
        $result->setObservedValuePrecision(0);

        return $result;
    }

    /**
     * Get the security status of the current WordPress installation.
     *
     * The method uses the official WordPress API for this check.
     *
     * @return mixed
     * @todo the API call should be cached for 1 hour.
     *
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
}

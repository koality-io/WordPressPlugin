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
class WordPressAdminUserCountCheck extends WordPressBasicCheck
{
    protected $configKey = 'koality_wordpress_system_admin_count';
    protected $configDefaultValue = 1;

    protected $resultKey = Result::KOALITY_IDENTIFIER_SECURITY_USERS_ADMIN_COUNT;

    protected $group = WordPressCheck::GROUP_SECURITY;
    protected $description = 'Check if the number of users with the role administrator is too high. Often WordPress gets attacked by injecting a new admin user that can see and change all data within the project.';
    protected $name = 'Number of admin user';

    protected $settings = [
        [
            'label' => 'Maximum number of administrators (users)',
            'required' => true,
            'args' => ['min' => 0]
        ]
    ];

    /**
     * @inheritDoc
     */
    protected function doRun()
    {
        $adminCount = $this->getAdministratorCount();

        $maxAdmin = (int)$this->getLimit();

        if ($adminCount > $maxAdmin) {
            $result = new MetricAwareResult(
                Result::STATUS_FAIL,
                'There are too many administrator accounts in your system.'
            );
        } else {
            $result = new MetricAwareResult(
                Result::STATUS_PASS,
                'The number of administrator accounts is within the limit'
            );
        }

        $result->setLimitType(Result::LIMIT_TYPE_MAX);
        $result->setLimit($maxAdmin);
        $result->setMetric($adminCount, 'administrators', MetricAwareResult::METRIC_TYPE_NUMERIC);
        $result->setObservedValuePrecision(0);

        return $result;
    }

    /**
     * Return the number of administrators.
     *
     * @return int
     */
    private function getAdministratorCount()
    {
        $administrators = get_users(array('role__in' => array('administrator')));
        return count($administrators);
    }
}

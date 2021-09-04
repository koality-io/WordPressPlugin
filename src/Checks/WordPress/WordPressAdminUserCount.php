<?php

namespace Koality\WordPressPlugin\Checks\WordPress;

use Koality\WordPressPlugin\Koality;
use Leankoala\HealthFoundationBase\Check\Check;
use Leankoala\HealthFoundationBase\Check\MetricAwareResult;
use Leankoala\HealthFoundationBase\Check\Result;

/**
 * Class WordPressInsecureCheck
 *
 * This check checks if the currently installed WordPress version is insecure.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-14
 */
class WordPressAdminUserCount implements Check
{
    /**
     * @inheritDoc
     */
    public function run()
    {
        $adminCount = $this->getAdministratorCount();

        $maxAdmin = (int)get_option(Koality::CONFIG_WORDPRESS_ADMIN_COUNT_KEY);

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
        $administrators = get_users( array( 'role__in' => array( 'administrator' ) ) );
        return count($administrators);
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return 'WordPressAdminUserCount';
    }
}

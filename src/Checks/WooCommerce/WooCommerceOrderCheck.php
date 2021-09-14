<?php

namespace Koality\WordPressPlugin\Checks\WooCommerce;

use Koality\WordPressPlugin\Koality;
use Leankoala\HealthFoundationBase\Check\Check;
use Leankoala\HealthFoundationBase\Check\MetricAwareResult;
use Leankoala\HealthFoundationBase\Check\Result;

/**
 * Class WordPressOrderCheck
 *
 * This check checks if there were enough orders within the last hour in the installed WooCommerce
 * shop.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-05
 */
class WooCommerceOrderCheck implements Check
{
    /**
     * @inheritDoc
     */
    public function run()
    {
        $isPeakTime = $this->isPeakTime();
        $orderCount = $this->getLastHourOrderCount();

        if ($isPeakTime) {
            $limit = get_option(Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_KEY);
        } else {
            $limit = get_option(Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_OFF_KEY);
        }

        if ($limit > $orderCount) {
            $result = new MetricAwareResult(
                Result::STATUS_FAIL,
                'Not enough orders within the last hour.'
            );
        } else {
            $result = new MetricAwareResult(
                Result::STATUS_PASS,
                'Enough orders within the last hour.'
            );
        }

        $result->setLimit($limit);
        $result->setMetric($orderCount, 'orders');
        $result->setObservedValuePrecision(0);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return 'WooCommerceOrderCheck';
    }

    /**
     * Return the number of orders that were done within the last hour.
     *
     * @return int
     */
    private function getLastHourOrderCount()
    {
        $query = new \WC_Order_Query(array(
            'date_created' => '>' . (time() - HOUR_IN_SECONDS),
            'return' => 'ids',
        ));

        $orders = $query->get_orders();

        return count($orders);
    }

    /**
     * Return true if the current time is within the defined peak time.
     *
     * @return bool
     */
    private function isPeakTime()
    {
        $peakStart = get_option(Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_START_KEY);
        $peakEnd = get_option(Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_END_KEY);
        $includeWeekend = false;

        $currentWeekDay = date('w');
        $isWeekend = ($currentWeekDay == 0 || $currentWeekDay == 6);

        $beginHour = $peakStart * 100;
        $endHour = $peakEnd * 100;

        $currentTime = (int)date('Hi');

        if (!$includeWeekend && $isWeekend) {
            return false;
        }

        if ($currentTime < $endHour && $currentTime > $beginHour) {
            return true;
        } else {
            return false;
        }
    }
}

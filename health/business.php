<?php

include 'init.php';

/**
 * Get the number of orders in the last hour.
 *
 * @return int
 */
function koality_getLastHourOrderCount()
{
    $query = new WC_Order_Query(array(
        'date_created' => '>' . (time() - HOUR_IN_SECONDS),
        'return' => 'ids',
    ));

    $orders = $query->get_orders();

    return count($orders);
}

/**
 * Return if the current time is within the peak time slot.
 *
 * @param int $peakStart
 * @param int $peakEnd
 * @param bool $includeWeekend
 *
 * @return bool
 */
function koality_isPeakTime()
{
    $peakStart = get_option(Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_START_KEY);
    $peakEnd = get_option(Koality::CONFIG_WOOCOMMERCE_RUSH_HOUR_END_KEY);
    $includeWeekend = false;

    $currentWeekDay = date('w');
    $isWeekend = ($currentWeekDay == 0 || $currentWeekDay == 6);

    $allowRushHour = !($isWeekend && !$includeWeekend);

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

$isPeakTime = koality_isPeakTime();
$orderCount = koality_getLastHourOrderCount();

if ($isPeakTime) {
    $limit = get_option(Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_KEY);
} else {
    $limit = get_option(Koality::CONFIG_WOOCOMMERCE_ORDER_PEAK_OFF_KEY);
}

if ($limit > $orderCount) {
    $orderCheck = new \Leankoala\HealthFoundation\Check\Basic\StaticCheck(
        \Leankoala\HealthFoundation\Check\Result::KOALITY_IDENTIFIER_ORDERS_TOO_FEW,
        \Leankoala\HealthFoundation\Check\Result::STATUS_FAIL,
        'Not enough orders within the last hour.'
    );
} else {
    $orderCheck = new \Leankoala\HealthFoundation\Check\Basic\StaticCheck(
        \Leankoala\HealthFoundation\Check\Result::KOALITY_IDENTIFIER_ORDERS_TOO_FEW,
        \Leankoala\HealthFoundation\Check\Result::STATUS_PASS,
        'Enough orders within the last hour.'
    );
}

$foundation->registerCheck($orderCheck);

$runResult = $foundation->runHealthCheck();

$formatter = new \Leankoala\HealthFoundation\Result\Format\Koality\KoalityFormat(
    'Storage server is up and running.',
    'Some problems occurred on storage server.'
);

$formatter->handle(
    $runResult
);

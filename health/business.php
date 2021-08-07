<?php

use Leankoala\HealthFoundation\Check\Result;
use Leankoala\HealthFoundation\HealthFoundation;
use Leankoala\HealthFoundation\Result\Format\Koality\KoalityFormat;

include 'init.php';

include_once __DIR__ . '/Check/WooCommerceOrderCheck.php';

/** @var HealthFoundation $foundation */
$foundation->registerCheck(new WooCommerceOrderCheck(), Result::KOALITY_IDENTIFIER_ORDERS_TOO_FEW);

$runResult = $foundation->runHealthCheck();

$formatter = new KoalityFormat(
    'WooCommerce business metrics look good.',
    'WooCommerce business metrics indicate problems.',
    $dataProtection
);

$formatter->handle(
    $runResult
);

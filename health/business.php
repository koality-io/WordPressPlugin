<?php

use Leankoala\HealthFoundation\Check\Result;
use Leankoala\HealthFoundation\HealthFoundation;
use Leankoala\HealthFoundation\Result\Format\Koality\KoalityFormat;

include 'init.php';

require_once __DIR__ . '/../../../../wp-admin/includes/admin.php';

include_once __DIR__ . '/Check/WooCommerceOrderCheck.php';
include_once __DIR__ . '/Check/WooCommerceProductsNumberCheck.php';
include_once __DIR__ . '/Check/WordPressPlugins.php';
include_once __DIR__ . '/Check/WordPressInsecure.php';

/** @var HealthFoundation $foundation */

$foundation->registerCheck(new WooCommerceOrderCheck(), Result::KOALITY_IDENTIFIER_ORDERS_TOO_FEW, '', 'business');
$foundation->registerCheck(new WooCommerceProductsNumberCheck(), Result::KOALITY_IDENTIFIER_PRODUCTS_COUNT, '', 'business');

$runResult = $foundation->runHealthCheck();

$formatter = new KoalityFormat(
    'WooCommerce business metrics look good.',
    'WooCommerce business metrics indicate problems.',
    $dataProtection
);

$formatter->handle(
    $runResult
);

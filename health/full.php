<?php

use Leankoala\HealthFoundation\Check\Device\SpaceUsedCheck;
use Leankoala\HealthFoundation\Check\Result;
use Leankoala\HealthFoundation\HealthFoundation;
use Leankoala\HealthFoundation\Result\Format\Koality\KoalityFormat;

include 'init.php';

require_once __DIR__ . '/../../../../wp-admin/includes/admin.php';

include_once __DIR__ . '/Check/WooCommerceOrderCheck.php';
include_once __DIR__ . '/Check/WooCommerceProductsNumberCheck.php';
include_once __DIR__ . '/Check/WordPressPlugins.php';
include_once __DIR__ . '/Check/WordPressInsecure.php';
include_once __DIR__ . '/Check/WordPressAdminUserCount.php';

/** @var HealthFoundation $foundation */

$uploadDir = wp_upload_dir()['basedir'];

// max disc usage 95%
$spaceUsedCheck = new SpaceUsedCheck();
$spaceUsedCheck->init(get_option(Koality::CONFIG_SYSTEM_SPACE_KEY), $uploadDir);

// Business
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    $foundation->registerCheck(new WooCommerceOrderCheck(), Result::KOALITY_IDENTIFIER_ORDERS_TOO_FEW, '', 'plugins.groups.business');
    $foundation->registerCheck(new WooCommerceProductsNumberCheck(), Result::KOALITY_IDENTIFIER_PRODUCTS_COUNT, '', 'plugins.groups.business');
}

// Server
$foundation->registerCheck($spaceUsedCheck, Result::KOALITY_IDENTIFIER_SERVER_DICS_SPACE_USED, '', 'plugins.groups.server');

// Security
$foundation->registerCheck(new WordPressInsecure(), Result::KOALITY_IDENTIFIER_SYSTEM_INSECURE, '', 'plugins.groups.security');
$foundation->registerCheck(new WordPressPlugins(), Result::KOALITY_IDENTIFIER_PLUGINS_UPDATABLE, '', 'plugins.groups.security');
$foundation->registerCheck(new WordPressAdminUserCount(), Result::KOALITY_IDENTIFIER_SECURITY_USERS_ADMIN_COUNT, '', 'plugins.groups.security');

$runResult = $foundation->runHealthCheck();

$formatter = new KoalityFormat(
    'WooCommerce business metrics look good.',
    'WooCommerce business metrics indicate problems.',
    $dataProtection
);

$formatter->handle(
    $runResult
);

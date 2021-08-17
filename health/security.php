<?php

use Leankoala\HealthFoundation\Check\Result;
use Leankoala\HealthFoundation\HealthFoundation;
use Leankoala\HealthFoundation\Result\Format\Koality\KoalityFormat;

include 'init.php';

include_once __DIR__ . '/Check/WordPressInsecure.php';
include_once __DIR__ . '/Check/WordPressAdminUserCount.php';

/** @var HealthFoundation $foundation */

$foundation->registerCheck(new WordPressInsecure(), Result::KOALITY_IDENTIFIER_SYSTEM_INSECURE);
$foundation->registerCheck(new WordPressAdminUserCount(), Result::KOALITY_IDENTIFIER_SECURITY_USERS_ADMIN_COUNT);

$runResult = $foundation->runHealthCheck();

$formatter = new KoalityFormat(
    'Storage server is up and running.',
    'Some problems occurred on storage server.',
    $dataProtection
);

$formatter->handle(
    $runResult
);

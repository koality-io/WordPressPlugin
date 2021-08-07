<?php

use Leankoala\HealthFoundation\Check\Device\SpaceUsedCheck;
use Leankoala\HealthFoundation\HealthFoundation;
use Leankoala\HealthFoundation\Result\Format\Koality\KoalityFormat;

include 'init.php';

/** @var HealthFoundation $foundation */

$uploadDir = wp_upload_dir()['basedir'];

// max disc usage 95%
$spaceUsedCheck = new SpaceUsedCheck();
$spaceUsedCheck->init(get_option(Koality::CONFIG_SYSTEM_SPACE_KEY), $uploadDir);

$foundation->registerCheck(
    $spaceUsedCheck,
    'space_used_check',
    'Space used on storage server');

$runResult = $foundation->runHealthCheck();

$formatter = new KoalityFormat(
    'Storage server is up and running.',
    'Some problems occurred on storage server.'
);

$formatter->handle(
    $runResult
);



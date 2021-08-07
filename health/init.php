<?php

use Leankoala\HealthFoundation\HealthFoundation;

require_once(__DIR__ . '/../../../../wp-config.php');
require_once(__DIR__ . '/../vendor/autoload.php');

$wp->init();
$wp->parse_request();
$wp->query_posts();
$wp->register_globals();
$wp->send_headers();

$apiKey = get_option(Koality::OPTION_API_KEY);

if (!$apiKey || $apiKey == 'off') {
    $result = [
        'status' => 'failure',
        'message' => 'No apiKey please activate the plugin.'
    ];
    die(json_encode($result, JSON_PRETTY_PRINT));
} else if (!array_key_exists('apiKey', $_GET)) {
    $result = [
        'status' => 'failure',
        'message' => 'No apiKey found in the request.'
    ];
    die(json_encode($result, JSON_PRETTY_PRINT));
} elseif ($_GET['apiKey'] !== $apiKey) {
    $result = [
        'status' => 'failure',
        'message' => 'The given API key is invalid.'
    ];
    die(json_encode($result, JSON_PRETTY_PRINT));
}

$foundation = new HealthFoundation();

$dataProtection = (bool)get_option(Koality::CONFIG_DATA_PROTECTION_KEY);

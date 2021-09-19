<?php

namespace Koality\WordPressPlugin\Rest;

use Koality\WordPressPlugin\Checks\WooCommerce\WooCommerceOrderCheck;
use Koality\WordPressPlugin\Checks\WooCommerce\WooCommerceProductsNumberCheck;
use Koality\WordPressPlugin\Checks\WordPress\WordPressAdminUserCount;
use Koality\WordPressPlugin\Checks\WordPress\WordPressInsecure;
use Koality\WordPressPlugin\Checks\WordPress\WordPressPlugins;
use Koality\WordPressPlugin\Koality;
use Leankoala\HealthFoundationBase\Check\Result;
use Leankoala\HealthFoundationBase\HealthFoundation;
use Leankoala\HealthFoundationBase\Result\Format\Koality\KoalityFormat;
use Leankoala\HealthFoundationChecks\Device\SpaceUsedCheck;

if (!defined('ABSPATH')) {
    exit;
}

include_once ABSPATH . 'wp-admin/includes/admin.php';

/**
 * Class KoalityApiEndpoint
 *
 * @package Koality\WordPressPlugin
 *
 * @author Nils Langner <nils.langner@webpros.com>
 * @author Sascha Fuchs <sascha.fuchs@webpros.com>
 *
 * created 2021-09-01
 */
class Health
{
    const API_KEY_KEY = 'apiKey';

    /**
     * Add the WordPress hooks.
     */
    public function addHooks()
    {
        add_action('rest_api_init', [$this, 'registerApiEndpoint']);
    }

    public function registerApiEndpoint()
    {
        register_rest_route('koality-io/v1', '/health',
            [
                'methods' => 'GET',
                'args' => [
                    self::API_KEY_KEY => [
                        'required' => true,
                    ],
                ],
                'callback' => [$this, 'health'],
                'permission_callback' => [$this, 'authorize'],
            ]
        );
    }

    public function health()
    {
        $foundation = new HealthFoundation();

        $dataProtection = (bool)get_option(Koality::CONFIG_DATA_PROTECTION_KEY);

        // Business
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            $foundation->registerCheck(new WooCommerceOrderCheck(), Result::KOALITY_IDENTIFIER_ORDERS_TOO_FEW, '', 'plugins.groups.business');
         }

        $container = Koality::getWordPressChecks();

        $container->connect($foundation);

        $runResult = $foundation->runHealthCheck();

        $formatter = new KoalityFormat(
            'WordPress checks look good.',
            'WordPress checks indicate problems.',
            $dataProtection
        );

        $data = $formatter->handle(
            $runResult,
            false
        );

        $restResponse = new \WP_REST_Response($data, 200);
        $restResponse->set_headers(['Cache-Control' => 'no-cache']);

        return $restResponse;
    }

    public function authorize(\WP_REST_Request $request)
    {
        $currentApiKey = $request->get_param(self::API_KEY_KEY);

        $apiKey = get_option(Koality::OPTION_API_KEY);

        if (!$apiKey || $apiKey == 'off') {
            return false;
        } elseif ($currentApiKey !== $apiKey) {
            return false;
        } else {
            return true;
        }
    }
}

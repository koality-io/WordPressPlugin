<?php

namespace Koality\WordPressPlugin\Rest\Actions;

use Koality\WordPressPlugin\Koality;
use Koality\WordPressPlugin\WordPress\Options;

if (!defined('WP_KOALITY_IO')) {
    exit;
}

/**
 * Class BaseAction
 *
 * @package Koality\WordPressPlugin\Rest\Actions
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-09-12
 */
abstract class BaseAction implements Action
{
    const API_KEY = 'apiKey';

    protected $routeMethod = 'GET';
    protected $routeArguments = [];
    protected $routeNamespace = 'koality-io/actions/v1';
    protected $routePath = '';

    public function addActions()
    {
        add_action('rest_api_init', [$this, 'registerAction']);
    }

    public function registerAction()
    {
        register_rest_route($this->routeNamespace, $this->routePath,
            [
                'methods' => $this->routeMethod,
                'args' => [
                    $this->routeArguments
                ],
                'callback' => [$this, 'run'],
                'permission_callback' => [$this, 'authorize']
            ]
        );
    }

    abstract public function run(\WP_REST_Request $request);

    public function authorize(\WP_REST_Request $request)
    {
        $currentApiKey = $request->get_param(self::API_KEY);

        $apiKey = Options::get(Koality::OPTION_API_KEY);

        if (!$apiKey || $apiKey == 'off') {
            return false;
        } elseif ($currentApiKey !== $apiKey) {
            return false;
        } else {
            return true;
        }
    }

    protected function returnSuccess($message, $rerunCheck = false, $removeAction = false)
    {
        $data = [
            'status' => 'success',
            'message' => $message,
            'rerunCheck' => $rerunCheck,
            'removeAction' => $removeAction
        ];

        $restResponse = new \WP_REST_Response($data, 200);
        $restResponse->set_headers(['Cache-Control' => 'no-cache']);

        return $restResponse;
    }


    protected function returnFailure($message, $error = 'unknown_error')
    {
        $restResponse = new \WP_Error($error, $message);
        return $restResponse;
    }

    protected function getActionBaseUrl()
    {
        $apiKey = Options::get(Koality::OPTION_API_KEY);
        return get_site_url() . '?rest_route=/' . $this->routeNamespace . '/' . $this->routePath . '&' . self::API_KEY . '=' . $apiKey;
    }
}

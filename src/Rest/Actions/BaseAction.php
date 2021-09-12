<?php

namespace Koality\WordPressPlugin\Rest\Actions;

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
    protected $routeMethod = 'GET';
    protected $routeArguments = [];
    protected $routeNamespace = 'koality-io/v1';
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
                'permission_callback' => [$this, 'authorize'],
            ]
        );
    }

    abstract public function run(\WP_REST_Request $request);

    protected function authorize(\WP_REST_Request $request)
    {
        return true;
    }

    protected function returnSuccess($message)
    {
        $data = [
            'status' => 'success',
            'message' => $message
        ];

        $restResponse = new \WP_REST_Response($data, 200);
        $restResponse->set_headers(['Cache-Control' => 'no-cache']);

        return $restResponse;
    }
}

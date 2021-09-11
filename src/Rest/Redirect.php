<?php

namespace Koality\WordPressPlugin\Rest;

if (!defined('ABSPATH')) {
    exit;
}

include_once ABSPATH . 'wp-admin/includes/admin.php';

/**
 * Class Redirect
 *
 * Redirect the user to the correct url.
 *
 * @package Koality\WordPressPlugin
 *
 * @author Nils Langner <nils.langner@webpros.com>
 *
 * created 2021-09-11
 */
class Redirect
{
    const URL_KEY_TARGET = 'target';

    const targets = [
        'plugins' => 'plugins.php',
        'comments_spam' => 'edit-comments.php?comment_status=spam',
        'comments_pending' => 'edit-comments.php?comment_status=moderated',
    ];

    /**
     * Add the WordPress hooks.
     */
    public function addHooks()
    {
        add_action('rest_api_init', [$this, 'registerApiEndpoint']);
    }

    public function registerApiEndpoint()
    {
        register_rest_route('koality-io/v1', '/redirect',
            [
                'methods' => 'GET',
                'args' => [
                    self::URL_KEY_TARGET => [
                        'required' => true,
                    ],
                ],
                'callback' => [$this, 'redirect']
            ]
        );
    }

    public function redirect(\WP_REST_Request $request)
    {
        $target = $request->get_param(self::URL_KEY_TARGET);

        try {
            $url = self::getUrl($target);
        } catch (\Exception $exception) {
            $data = ['status' => 'error', 'message' => $exception->getMessage()];
            $restResponse = new \WP_REST_Response($data, 404);
            $restResponse->set_headers(['Cache-Control' => 'no-cache']);

            return $restResponse;
        }

        wp_redirect($url);

        exit();
    }

    /**
     * Return the correct route for the given target.
     *
     * @param string $target
     * @return string
     */
    static public function getUrl($target)
    {
        $adminUrl = admin_url();

        if (!array_key_exists($target, self::targets)) {
            throw new \RuntimeException('No route for target "' . $target . '" found');
        }

        return $adminUrl . self::targets[$target];
    }
}

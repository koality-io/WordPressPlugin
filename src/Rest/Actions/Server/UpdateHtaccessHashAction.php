<?php

namespace Koality\WordPressPlugin\Rest\Actions\Server;

use Koality\WordPressPlugin\Rest\Actions\BaseAction;
use Koality\WordPressPlugin\WordPress\Options;

if (!defined('WP_KOALITY_IO')) {
    exit;
}

/**
 * Class UpdateHtaccessHashAction
 *
 * @package Koality\WordPressPlugin\Rest\Actions\Server
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-09-12
 */
class UpdateHtaccessHashAction extends BaseAction
{
    const HTACCESS_HASH = 'koality_server_htaccess_hash';

    protected $routePath = 'server/htaccess-update';

    public function run(\WP_REST_Request $request)
    {
        self::updateHash(self::getCurrentHash());

        return $this->returnSuccess('Current .htaccess successfully marked as valid.');
    }

    static public function updateHash($hash)
    {
        Options::set(self::HTACCESS_HASH, $hash);
    }

    static function getCurrentHash()
    {
        $rootPath = get_home_path();

        $htaccessFile = $rootPath . '/.htaccess';

        if (!file_exists($htaccessFile)) {
            $htaccessContent = "no_htaccess_file_found";
        } else {
            $htaccessContent = file_get_contents($htaccessFile);
        }

        return md5($htaccessContent);
    }

    public function getActionUrl()
    {
        return $this->getActionBaseUrl();
    }
}

<?php

namespace Koality\WordPressPlugin\WordPress\Exception;

if (!defined('WP_KOALITY_IO')) {
    exit;
}
/**
 * Class WordPressException
 *
 * @package Koality\WordPressPlugin\WordPress\Exception
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-09-12
 */
class WordPressException extends \Exception
{
    private $wpError;

    public function setWpError(\WP_Error $error)
    {
        $this->wpError = $error;
    }

    /**
     * @return \Wp_Error
     */
    public function getWpError()
    {
        return $this->wpError;
    }

}

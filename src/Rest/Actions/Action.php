<?php

namespace Koality\WordPressPlugin\Rest\Actions;

if (!defined('WP_KOALITY_IO')) {
    exit;
}

/**
 * Interface Action
 *
 * @package Koality\WordPressPlugin\Rest\Actions
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-09-12
 */
interface Action
{
    public function addActions();
}

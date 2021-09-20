<?php

namespace Koality\WordPressPlugin\Checks;

use Leankoala\HealthFoundationBase\Check\Check;

/**
 * Interface WordPressCheck
 *
 * @package Koality\WordPressPlugin\Checks
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-09-02
 */
interface WordPressCheck extends Check
{
    const GROUP_SECURITY = 'plugins.groups.security';
    const GROUP_CONTENT = 'plugins.groups.content';
    const GROUP_SYSTEM = 'plugins.groups.system';
    const GROUP_SERVER = 'plugins.groups.server';
    const GROUP_CUSTOM = 'plugins.groups.custom';
    const GROUP_BUSINESS = 'plugins.groups.business';
    const GROUP_PLUGINS = 'plugins.groups.plugins';

    /**
     * Return the WordPress internal settings key.
     *
     * @return string
     */
    public function getConfigKey();

    /**
     * Return the default settings value. This is needed when not value is set
     * or when the
     *
     * @return mixed
     */
    public function getConfigDefaultValue();

    /**
     * Return the key for the koality.io result element. This is used for the translation in
     * koality.io.
     *
     * @return string
     */
    public function getResultKey();

    /**
     * Return the group of the check.
     *
     * This will be used in the WordPress backend for the settings sections and the
     * correct page and is also used in koality.io to set the group in the result page.
     *
     * @return string
     */
    public function getGroup();

    /**
     * Return the group name as an human readable string.
     *
     * @return string
     */
    public function getGroupAsString();

    /**
     * Return the description for the check.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Return the name for the check.
     *
     * @return string
     */
    public function getName();

    /**
     * Return the settings of the check.
     *
     * @return array
     */
    public function getSettings();

    /**
     * Return the unique identifier for the check.
     *
     * @return mixed
     */
    public function getIdentifier();

    /**
     * Return true if the check is enabled by default
     *
     * @return mixed
     */
    public function isEnabledByDefault();
}

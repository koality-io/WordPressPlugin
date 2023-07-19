<?php

namespace Koality\WordPressPlugin\Checks;

use Koality\WordPressPlugin\Admin\Admin;
use Koality\WordPressPlugin\WordPress\Options;
use Leankoala\HealthFoundationBase\HealthFoundation;

/**
 * Class Container
 *
 * @package Koality\WordPressPlugin\Checks
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-09-02
 */
class WordPressCheckContainer
{
    /**
     * @var WordPressCheck[]
     */
    private $checks = [];

    public function addWordPressCheck(WordPressCheck $check)
    {
        $this->checks[] = $check;
    }

    public function connect(HealthFoundation $foundation, $filterDisabled = true)
    {
        $enabledChecks = Options::get(Admin::ENABLED_KEY);

        if (is_bool($enabledChecks)) {
            return;
        }

        foreach ($this->checks as $check) {
            if (!$filterDisabled || array_key_exists($check->getIdentifier(), $enabledChecks)) {
                $foundation->registerCheck($check, $check->getResultKey(), $check->getDescription(), $check->getGroup());
            }
        }
    }

    public function getSettings()
    {
        $settings = [];
        foreach ($this->checks as $check) {
            $settings = array_merge($settings, $check->getSettings());
        }

        return $settings;
    }

    /**
     * @return WordPressCheck[]
     */
    public function getChecks()
    {
        return $this->checks;
    }
}

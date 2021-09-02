<?php

namespace Koality\WordPressPlugin\Checks;

use Leankoala\HealthFoundation\HealthFoundation;

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

    public function connect(HealthFoundation $foundation)
    {
        foreach ($this->checks as $check) {
            $foundation->registerCheck($check, $check->getResultKey(), $check->getDescription(), $check->getGroup());
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

<?php

namespace Koality\WordPressPlugin\Checks;

/**
 * Class WordPressOrderCheck
 *
 * This check checks if there where enough orders within the last hour in the installed WooCommerce
 * shop.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-05
 */
abstract class WordPressBasicCheck implements WordPressCheck
{
    protected $configKey = 'koality.wordpress.comments.pending';
    protected $resultKey = 'comments.pending';

    protected $group = WordPressCheck::GROUP_CONTENT;
    protected $description = '';
    protected $configDefaultValue = 0;

    protected $settings = [];

    public function getConfigKey()
    {
        return $this->configKey;
    }

    public function getResultKey()
    {
        return $this->resultKey;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function getConfigDefaultValue()
    {
        return $this->configDefaultValue;
    }

    public function getIdentifier()
    {
        return $this->getResultKey();
    }

    public function getSettings()
    {
        $settings = [];

        switch ($this->getGroup()) {
            case WordPressCheck::GROUP_CONTENT:
                $page = 'koality_content_settings';
                break;
            case WordPressCheck::GROUP_SECURITY:
                $page = 'koality_security_settings';
                break;
            case WordPressCheck::GROUP_SERVER:
                $page = 'koality_server_settings';
                break;
            default:
                $page = 'koality_' . $this->getGroup() . '_settings';
        }

        foreach ($this->settings as $setting) {
            $newSettings = array_merge($setting, [
                'page' => $page,
                'identifier' => $this->getConfigKey(),
                'section' => 'koality_general_section',
                'args' => []
            ]);

            if (array_key_exists('required', $newSettings)) {
                if ($newSettings['required']) {
                    $newSettings['required'] = 'true';
                } else {
                    $newSettings['required'] = 'false';
                }
            } else {
                $newSettings['required'] = 'false';
            }

            $settings[] = $newSettings;
        }

        return $settings;
    }

    protected function getLimit()
    {
        $limit = get_option($this->getConfigKey());

        if (is_null($limit)) {
            $limit = $this->getConfigDefaultValue();
        }

        return $limit;
    }
}

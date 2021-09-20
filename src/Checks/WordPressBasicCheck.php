<?php

namespace Koality\WordPressPlugin\Checks;

use Koality\WordPressPlugin\Rest\Redirect;
use Leankoala\HealthFoundationBase\Check\Action;

/**
 * Class WordPressBasicCheck
 *
 * Base class for most of the checks.
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
    protected $name = '';

    protected $settings = [];

    protected $target = false;
    protected $targetLabel = "";

    protected $enabledByDefault = true;

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
            case WordPressCheck::GROUP_CUSTOM:
                $page = 'koality_custom_settings';
                break;
            case WordPressCheck::GROUP_SYSTEM:
                $page = 'koality_system_settings';
                break;
            case WordPressCheck::GROUP_BUSINESS:
                $page = 'koality_woocommerce_settings';
                break;
            default:
                $page = 'koality_' . $this->getGroup() . '_settings';
        }

        foreach ($this->settings as $setting) {

            $newSettings = array_merge([
                'page' => $page,
                'identifier' => $this->getConfigKey(),
                'section' => 'koality_general_section',
                'args' => []
            ], $setting);

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

    public function run()
    {
        $result = $this->doRun();

        if ($this->target) {
            try {
                $url = Redirect::getUrl($this->target);
                $result->addAction(new Action($this->targetLabel, $url, Action::TYPE_LINK));
            } catch (\Exception $exception) {
                $result->addAttribute('error', 'No route for target ' . $this->target . ' found.');
            }
        }

        return $result;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    abstract protected function doRun();

    /**
     * @inheritDoc
     */
    public function getGroupAsString()
    {
        switch ($this->getGroup()) {
            case WordPressCheck::GROUP_BUSINESS:
                return 'Business';
            case WordPressCheck::GROUP_SECURITY:
                return 'Security';
            case WordPressCheck::GROUP_SYSTEM:
                return 'System';
            case WordPressCheck::GROUP_CONTENT:
                return 'Content';
            case WordPressCheck::GROUP_SERVER:
                return 'Server';
            case WordPressCheck::GROUP_PLUGINS:
                return 'Plugins';
            default:
                return $this->getGroup();
        }
    }

    /**
     * @inheritDoc
     */
    public function isEnabledByDefault()
    {
        return $this->enabledByDefault;
    }
}

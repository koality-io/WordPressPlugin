<?php

namespace Koality\WordPressPlugin\Checks\Server;

use Koality\WordPressPlugin\Checks\WordPressBasicCheck;
use Koality\WordPressPlugin\Checks\WordPressCheck;
use Leankoala\HealthFoundationBase\Check\Result;

/**
 * Class WordPressOrderCheck
 *
 * This check checks if there are enough active products in the shop.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-05
 */
class SpaceUsedCheck extends WordPressBasicCheck
{
    protected $configKey = 'koality_system_space';
    protected $configDefaultValue = 95;

    protected $resultKey = Result::KOALITY_IDENTIFIER_SERVER_DISC_SPACE_USED;

    protected $group = WordPressCheck::GROUP_SERVER;
    protected $description = 'Check if there is enough space on the disc left.';
    protected $name = 'Space used check';

    protected $settings = [
        [
            'label' => 'Maximum space usage (%)',
            'required' => true,
            'args' => ['min' => 0, 'max' => 100]
        ]
    ];

    /**
     * @inheritDoc
     */
    protected function doRun()
    {
        $uploadDir = wp_upload_dir()['basedir'];

        $limit = $this->getLimit();

        // max disc usage 95%
        $spaceUsedCheck = new \Leankoala\HealthFoundationChecks\Device\SpaceUsedCheck();
        $spaceUsedCheck->init($limit, $uploadDir);

        return $spaceUsedCheck->run();
    }
}

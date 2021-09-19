<?php

namespace Koality\WordPressPlugin\Checks\Server;

use Koality\WordPressPlugin\Checks\WordPressBasicCheck;
use Koality\WordPressPlugin\Checks\WordPressCheck;
use Koality\WordPressPlugin\Rest\Actions\Server\UpdateHtaccessHashAction;
use Koality\WordPressPlugin\WordPress\Options;
use Leankoala\HealthFoundationBase\Check\Action;
use Leankoala\HealthFoundationBase\Check\MetricAwareResult;
use Leankoala\HealthFoundationBase\Check\Result;

/**
 * Class WordPressOrderCheck
 *
 * Check if there was a change in the htaccess file.
 *
 * It also comes with an action to update the files hash in the database.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-05
 */
class HtaccessChangesCheck extends WordPressBasicCheck
{
    protected $resultKey = Result::KOALITY_IDENTIFIER_SERVER_HTACCESS_CHANGE;

    protected $group = WordPressCheck::GROUP_SERVER;
    protected $description = 'Check if the .htaccess file got changed. Often there are attacks on WordPress that add redirects to other websites.';
    protected $name = '.htaccess change';

    protected $enabledByDefault = false;

    /**
     * @inheritDoc
     */
    protected function doRun()
    {
        $currentContentHash = UpdateHtaccessHashAction::getCurrentHash();
        $expectedContentHash = $this->getExpectedHash($currentContentHash);

        $hasChanged = $expectedContentHash != $currentContentHash;

        if ($hasChanged) {
            $result = new MetricAwareResult(
                Result::STATUS_FAIL,
                'The .htaccess has changed.'
            );

            $action = new UpdateHtaccessHashAction();

            $result->addAction(new Action('Mark current .htaccess as valid', $action->getActionUrl(), Action::TYPE_REST));

        } else {
            $result = new MetricAwareResult(
                Result::STATUS_PASS,
                'The .htaccess has not changed.'
            );
        }

        $result->setLimit(0);
        $result->setMetric($hasChanged ? 1 : 0, 'changes');
        $result->setObservedValuePrecision(0);

        return $result;
    }

    private function getExpectedHash($currentContentHash)
    {
        $expectedContentHash = Options::get(UpdateHtaccessHashAction::HTACCESS_HASH);

        if (!$expectedContentHash) {
            $expectedContentHash = $currentContentHash;
            UpdateHtaccessHashAction::updateHash($expectedContentHash);
        }

        return $expectedContentHash;
    }
}

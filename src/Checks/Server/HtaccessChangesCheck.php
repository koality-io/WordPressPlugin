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
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-05
 */
class HtaccessChangesCheck extends WordPressBasicCheck
{
    protected $resultKey = Result::KOALITY_IDENTIFIER_SERVER_HTACCESS_CHANGE;

    protected $group = WordPressCheck::GROUP_SERVER;
    protected $description = '';

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

            $result->addAction(new Action('Mark current .htaccess as valid', $action->getActionUrl(),));

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

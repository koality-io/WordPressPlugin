<?php

namespace Koality\WordPressPlugin\Checks\WordPress;

use Koality\WordPressPlugin\Checks\WordPressBasicCheck;
use Koality\WordPressPlugin\Checks\WordPressCheck;
use Leankoala\HealthFoundationBase\Check\MetricAwareResult;
use Leankoala\HealthFoundationBase\Check\Result;

/**
 * Class WordPressCommentsPendingCheck
 *
 * Check if there are too many pending comments in the system.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-05
 */
class WordPressCommentsSpamCheck extends WordPressBasicCheck
{
    protected $configKey = 'koality_wordpress_comments_spam';
    protected $configDefaultValue = 0;

    protected $resultKey = 'comments.spam';

    protected $group = WordPressCheck::GROUP_CONTENT;
    protected $description = '';

    protected $settings = [
        [
            'label' => 'Maximum number of spam comments',
            'required' => true
        ]
    ];

    /**
     * @inheritDoc
     */
    public function run()
    {
        $commentCount = \wp_count_comments();

        $pendingCommentsCount = $commentCount->spam;

        $limit = $this->getLimit();

        if ($limit < $pendingCommentsCount) {
            $result = new MetricAwareResult(Result::STATUS_FAIL, 'Too many spam comments.');
        } else {
            $result = new MetricAwareResult(Result::STATUS_PASS, 'Not too many spam comments.');
        }

        $result->setLimitType(Result::LIMIT_TYPE_MAX);
        $result->setLimit($limit);
        $result->setMetric($pendingCommentsCount, 'comments');
        $result->setObservedValuePrecision(0);

        return $result;
    }
}

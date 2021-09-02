<?php

namespace Koality\WordPressPlugin\Checks;

use Leankoala\HealthFoundation\Check\MetricAwareResult;
use Leankoala\HealthFoundation\Check\Result;

/**
 * Class WordPressCommentsPendingCheck
 *
 * Check if there are too many pending comments in the system.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-05
 */
class WordPressCommentsPendingCheck extends WordPressBasicCheck
{
    protected $configKey = 'koality_wordpress_comments_pending';
    protected $configDefaultValue = 0;

    protected $resultKey = 'comments.pending';

    protected $group = WordPressCheck::GROUP_CONTENT;
    protected $description = '';

    protected $settings = [
        [
            'label' => 'Maximum number of pending comments',
            'required' => true
        ]
    ];

    /**
     * @inheritDoc
     */
    public function run()
    {
        $commentCount = \wp_count_comments();

        $pendingCommentsCount = $commentCount->moderated;

        $limit = $this->getLimit();

        if ($limit < $pendingCommentsCount) {
            $result = new MetricAwareResult(
                Result::STATUS_FAIL,
                'Too many pending comments.'
            );
        } else {
            $result = new MetricAwareResult(
                Result::STATUS_PASS,
                'Not too many pending comments.'
            );
        }

        $result->setLimitType(Result::LIMIT_TYPE_MAX);
        $result->setLimit($limit);
        $result->setMetric($pendingCommentsCount, 'comments');
        $result->setObservedValuePrecision(0);

        return $result;
    }
}

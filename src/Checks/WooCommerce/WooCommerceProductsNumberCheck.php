<?php

namespace Koality\WordPressPlugin\Checks\WooCommerce;

use Koality\WordPressPlugin\Checks\WordPressBasicCheck;
use Koality\WordPressPlugin\Checks\WordPressCheck;
use Leankoala\HealthFoundationBase\Check\MetricAwareResult;
use Leankoala\HealthFoundationBase\Check\Result;

/**
 * Class WordPressOrderCheck
 *
 * This check checks if there are enough active products in the shop.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-05
 */
class WooCommerceProductsNumberCheck extends WordPressBasicCheck
{
    protected $configKey = 'koality_woocommerce_product_count';
    protected $configDefaultValue = 1;

    protected $resultKey = Result::KOALITY_IDENTIFIER_PRODUCTS_COUNT;

    protected $group = WordPressCheck::GROUP_BUSINESS;
    protected $description = 'Check if the number of products in the WooCommerce store is high enough. Sometime when an import fails there are missing products and this check will help find that problem. ';
    protected $name = 'WooCommerce number of products';

    protected $settings = [
        [
            'label' => 'Minimum number of products',
            'required' => true,
            'args' => ['min' => 0]
        ]
    ];

    /**
     * @inheritDoc
     */
    protected function doRun()
    {
        $productCount = $this->getProductCount();

        $limit = $this->getLimit();

        if ($limit > $productCount) {
            $result = new MetricAwareResult(
                Result::STATUS_FAIL,
                'Not enough products in the WooCommerce shop.'
            );
        } else {
            $result = new MetricAwareResult(
                Result::STATUS_PASS,
                'Enough products in the WooCommerce shop.'
            );
        }

        $result->setLimit($limit);
        $result->setMetric($productCount, 'products', MetricAwareResult::METRIC_TYPE_NUMERIC);
        $result->setLimitType(Result::LIMIT_TYPE_MIN);
        $result->setObservedValuePrecision(0);

        return $result;
    }

    /**
     * Return the number of published products
     *
     * @return int
     */
    private function getProductCount()
    {
        $args = array('post_type' => 'product', 'post_status' => 'publish',
            'posts_per_page' => -1);
        $products = new \WP_Query($args);
        return $products->found_posts;
    }
}

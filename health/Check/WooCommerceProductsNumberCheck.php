<?php

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\MetricAwareResult;
use Leankoala\HealthFoundation\Check\Result;

/**
 * Class WordPressOrderCheck
 *
 * This check checks if there where enough orders within the last hour in the installed WooCommerce
 * shop.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-05
 */
class WooCommerceProductsNumberCheck implements Check
{
    /**
     * @inheritDoc
     */
    public function run()
    {
        $productCount = $this->getProductCount();

        $limit = get_option(Koality::CONFIG_WOOCOMMERCE_PRODUCT_COUNT_KEY);

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
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return 'WooCommerceOrderCheck';
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
        $products = new WP_Query($args);
        return $products->found_posts;
    }
}

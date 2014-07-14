<?php

namespace CL\Purchases;

use Harp\Harp\Config;
use Harp\Harp\Rel;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class ProductItem extends OrderItem
{
    public static function initialize(Config $config)
    {
        parent::initialize($config);

        $config
            ->addRels([
                new Rel\BelongsTo('product', $config, Product::getRepo(), ['key' => 'refId']),
            ]);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->getProduct()->name;
    }

    /**
     * @return \SebastianBergmann\Money\Money
     */
    public function getSourceValue()
    {
        return $this->getProduct()->getValue();
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->get('product');
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product)
    {
        $this->set('product', $product);

        return $this;
    }
}

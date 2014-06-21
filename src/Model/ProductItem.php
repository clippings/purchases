<?php

namespace CL\Purchases\Model;

use CL\Purchases\Repo;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class ProductItem extends BasketItem
{
    /**
     * @return Repo\ProductItem
     */
    public function getRepo()
    {
        return Repo\ProductItem::get();
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
        return $this->getLink('product')->get();
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product)
    {
        return $this->getLink('product')->set($product);
    }
}

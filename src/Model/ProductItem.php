<?php

namespace CL\Purchases\Model;

use CL\Purchases\Repo;
use CL\CurrencyConvert\Converter;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class ProductItem extends BasketItem
{
    public function getRepo()
    {
        return Repo\ProductItem::get();
    }

    public function getPrice()
    {
        if ($this->isFrozen) {
            return parent::getPrice();
        } else {
            return $this->getProductPrice();
        }
    }

    public function getProductPrice()
    {
        $price = $this->getProduct()->getPrice();
        $currency = $this->getCurrency();

        if ($currency != $price->getCurrency()) {
            $price = Converter::get()->convert($price, $currency);
        }

        return $price;
    }

    public function setProduct(Product $product)
    {
        return $this->getLink('product')->set($product);
    }

    public function getProduct()
    {
        return $this->getLink('product')->get();
    }
}

<?php

namespace CL\Purchases\Model;

use Harp\Harp\AbstractModel;
use CL\Purchases\Repo;
use SebastianBergmann\Money\Currency;
use SebastianBergmann\Money\Money;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Product extends AbstractModel
{
    public function getRepo()
    {
        return Repo\Product::get();
    }

    public $id;
    public $storeId;
    public $title;
    public $price = 0;
    public $currency = 'GBP';

    public function getPrice()
    {
        return new Money($this->price, new Currency($this->currency));
    }

    public function setPrice(Money $price)
    {
        $this->price = $price->getAmount();

        return $this;
    }

    public function getBasketItems()
    {
        return $this->getLink('basketItems');
    }

    public function getStore()
    {
        return $this->getLink('store')->get();
    }

    public function setStore(Store $store)
    {
        $this->getLink('store')->set($store);

        return $this;
    }
}

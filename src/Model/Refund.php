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
class Refund extends AbstractModel
{
    public function getRepo()
    {
        return Repo\Refund::get();
    }

    public $id;
    public $basketId;
    public $deletedAt;

    public function getTotalPrice()
    {
        $prices = $this->getItems()->get()->pluckProperty('price');

        return new Money(array_sum($prices), $this->getCurrency());
    }

    public function getCurrency()
    {
        return $this->getBasket()->getCurrency();
    }

    public function getBasket()
    {
        return $this->getLink('basket')->get();
    }

    public function setBasket(Basket $basket)
    {
        return $this->getLink('basket')->set($basket);
    }

    public function getItems()
    {
        return $this->getLink('items');
    }
}

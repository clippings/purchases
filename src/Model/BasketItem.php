<?php

namespace CL\Purchases\Model;

use Harp\Harp\AbstractModel;
use CL\Purchases\Repo;
use Harp\Transfer\Model\AbstractItem;
use SebastianBergmann\Money;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class BasketItem extends AbstractItem
{
    public $class;
    public $purchaseId;

    public function getRepo()
    {
        return Repo\BasketItem::get();
    }

    public function getCurrency()
    {
        return $this->getBasket()->getCurrency();
    }

    public function getSourceValue()
    {
        return new Money($this->value, $this->getCurrency());
    }

    public function getTotalValue()
    {
        return $this->getValue()->multiply($this->quantity);
    }

    public function getPurchase()
    {
        return $this->getLink('purchase')->get();
    }

    public function setPurchase(Purchase $purchase)
    {
        return $this->getLink('purchase')->set($purchase);
    }

    public function getBasket()
    {
        return $this->getLink('basket')->get();
    }

    public function setBasket(Basket $basket)
    {
        return $this->getLink('basket')->set($basket);
    }
}

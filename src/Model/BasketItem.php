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
class BasketItem extends AbstractModel
{
    public $id;
    public $basketId;
    public $refId;
    public $quantity = 1;
    public $price = 0;
    public $isFrozen = false;
    public $class;
    public $deletedAt;

    /**
     * @return string
     */
    public function getName()
    {
        return 'Item';
    }

    public function getRepo()
    {
        return Repo\BasketItem::get();
    }

    public function getPrice()
    {
        return new Money($this->price, $this->getCurrency());
    }

    public function getCurrency()
    {
        $currency = $this->getBasket()->currency;

        return new Currency($currency);
    }

    public function freeze()
    {
        if (! $this->isFrozen) {
            $this->price = $this->getPrice()->getAmount();
            $this->isFrozen = true;
        }

        return $this;
    }

    public function unfreeze()
    {
        $this->isFrozen = false;

        return $this;
    }

    public function setPrice(Money $price)
    {
        $this->price = $price->getAmount();
    }

    public function getTotalPrice()
    {
        return $this->getPrice()->multiply($this->quantity);
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

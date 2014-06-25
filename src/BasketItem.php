<?php

namespace CL\Purchases;

use Harp\Harp\AbstractModel;
use Harp\Core\Model\InheritedTrait;
use CL\Transfer\AbstractItem;
use SebastianBergmann\Money\Money;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class BasketItem extends AbstractItem
{
    const REPO = 'CL\Purchases\BasketItemRepo';

    use InheritedTrait;

    public $purchaseId;

    /**
     * @return \SebastianBergmann\Money\Currency
     */
    public function getCurrency()
    {
        return $this->getBasket()->getCurrency();
    }

    /**
     * @return Money
     */
    public function getSourceValue()
    {
        return new Money($this->value, $this->getCurrency());
    }

    /**
     * Value * Quantity
     * @return Money
     */
    public function getTotalValue()
    {
        return $this->getValue()->multiply($this->quantity);
    }

    /**
     * @return Purchase
     */
    public function getPurchase()
    {
        return $this->getLink('purchase')->get();
    }

    /**
     * @param Purchase $purchase
     */
    public function setPurchase(Purchase $purchase)
    {
        return $this->getLink('purchase')->set($purchase);
    }

    /**
     * @return Basket
     */
    public function getBasket()
    {
        return $this->getLink('basket')->get();
    }

    /**
     * @param Basket $basket
     */
    public function setBasket(Basket $basket)
    {
        return $this->getLink('basket')->set($basket);
    }
}

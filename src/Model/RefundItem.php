<?php

namespace CL\Purchases\Model;

use Harp\Harp\AbstractModel;
use CL\Purchases\Repo;
use CL\Transfer\Model\AbstractItem;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class RefundItem extends AbstractItem
{
    /**
     * @return Repo\RefundItem
     */
    public function getRepo()
    {
        return Repo\RefundItem::get();
    }

    /**
     * @return Refund
     */
    public function getRefund()
    {
        return $this->getLink('refund')->get();
    }

    /**
     * @param Refund $refund
     */
    public function setRefund(Refund $refund)
    {
        return $this->getLink('refund')->set($refund);
    }

    /**
     * @return Item
     */
    public function getItem()
    {
        return $this->getLink('item')->get();
    }

    /**
     * @param BasketItem $item
     */
    public function setItem(BasketItem $item)
    {
        return $this->getLink('item')->set($item);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getItem()->getName();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        $description = $this->getItem()->getDescription();

        return "Refund for {$description}";
    }

    /**
     * @return \SebastianBergmann\Money\Currency
     */
    public function getCurrency()
    {
        return $this->getRefund()->getCurrency();
    }

    /**
     * @return \SebastianBergmann\Money\Money
     */
    public function getSourceValue()
    {
        return $this->getItem()->getValue();
    }
}

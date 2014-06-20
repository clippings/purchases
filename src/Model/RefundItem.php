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
    public function getRepo()
    {
        return Repo\RefundItem::get();
    }

    public function getRefund()
    {
        return $this->getLink('refund')->get();
    }

    public function setRefund(Refund $refund)
    {
        return $this->getLink('refund')->set($refund);
    }

    public function getItem()
    {
        return $this->getLink('item')->get();
    }

    public function setItem(BasketItem $item)
    {
        return $this->getLink('item')->set($item);
    }

    public function getName()
    {
        return $this->getItem()->getId();
    }

    public function getDescription()
    {
        $itemName = $this->getItem()->getName();

        return "Refund for {$itemName}";
    }

    public function getCurrency()
    {
        return $this->getRefund()->getCurrency();
    }

    public function getSourceValue()
    {
        return $this->getItem()->getValue();
    }
}

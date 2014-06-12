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
class RefundItem extends BasketItem
{
    public $refundId;

    public function getName()
    {
        $itemName = $this->getItem()->getName();

        return "Refund for {$itemName}";
    }

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
}

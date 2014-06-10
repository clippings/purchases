<?php

namespace CL\Purchases\Test\Repo;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Repo\RefundItem;

/**
 * @coversDefaultClass CL\Purchases\Repo\RefundItem
 */
class RefundItemTest extends AbstractTestCase
{
    /**
     * @covers ::newInstance
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $refundItem = RefundItem::newInstance();

        $billing = $refundItem->getRelOrError('basket');
        $this->assertInstanceOf('CL\Purchases\Repo\Basket', $billing->getForeignRepo());

        $items = $refundItem->getRelOrError('purchase');
        $this->assertInstanceOf('CL\Purchases\Repo\Purchase', $items->getForeignRepo());

        $purchases = $refundItem->getRelOrError('refundItem');
        $this->assertInstanceOf('CL\Purchases\Repo\RefundItem', $purchases->getForeignRepo());

        $refund = $refundItem->getRelOrError('refund');
        $this->assertInstanceOf('CL\Purchases\Repo\Refund', $refund->getForeignRepo());

        $item = $refundItem->getRelOrError('item');
        $this->assertInstanceOf('CL\Purchases\Repo\BasketItem', $item->getForeignRepo());

        $model = $refundItem->newModel();

        $this->assertInstanceOf('CL\Purchases\Model\RefundItem', $model);
    }
}

<?php

namespace CL\Purchases\Test\Repo;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Repo\BasketItem;

/**
 * @coversDefaultClass CL\Purchases\Repo\BasketItem
 */
class BasketItemTest extends AbstractTestCase
{
    /**
     * @covers ::newInstance
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $basket = BasketItem::newInstance();

        $billing = $basket->getRelOrError('basket');
        $this->assertInstanceOf('CL\Purchases\Repo\Basket', $billing->getForeignRepo());

        $items = $basket->getRelOrError('purchase');
        $this->assertInstanceOf('CL\Purchases\Repo\Purchase', $items->getForeignRepo());

        $purchases = $basket->getRelOrError('refundItem');
        $this->assertInstanceOf('CL\Purchases\Repo\RefundItem', $purchases->getForeignRepo());

        $model = $basket->newModel();

        $this->assertInstanceOf('CL\Purchases\Model\BasketItem', $model);
    }
}

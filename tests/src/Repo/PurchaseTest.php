<?php

namespace CL\Purchases\Test\Repo;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Repo\Purchase;

/**
 * @coversDefaultClass CL\Purchases\Repo\Purchase
 */
class PurchaseTest extends AbstractTestCase
{
    /**
     * @covers ::newInstance
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $purchase = Purchase::newInstance();

        $store = $purchase->getRelOrError('store');
        $this->assertInstanceOf('CL\Purchases\Repo\Store', $store->getForeignRepo());

        $basket = $purchase->getRelOrError('basket');
        $this->assertInstanceOf('CL\Purchases\Repo\Basket', $basket->getForeignRepo());

        $items = $purchase->getRelOrError('items');
        $this->assertInstanceOf('CL\Purchases\Repo\BasketItem', $items->getForeignRepo());

        $model = $purchase->newModel();

        $this->assertInstanceOf('CL\Purchases\Model\Purchase', $model);
    }
}

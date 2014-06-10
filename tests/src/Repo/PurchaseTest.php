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

        $basketItems = $purchase->getRelOrError('basketItems');
        $this->assertInstanceOf('CL\Purchases\Repo\BasketItem', $basketItems->getForeignRepo());

        $model = $purchase->newModel();

        $this->assertInstanceOf('CL\Purchases\Model\Purchase', $model);
    }
}

<?php

namespace CL\Purchases\Test;

use CL\Purchases\BasketRepo;

/**
 * @coversDefaultClass CL\Purchases\BasketRepo
 */
class BasketRepoTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $basket = new BasketRepo();

        $billing = $basket->getRelOrError('billing');
        $this->assertInstanceOf('CL\Purchases\AddressRepo', $billing->getForeignRepo());

        $items = $basket->getRelOrError('items');
        $this->assertInstanceOf('CL\Purchases\BasketItemRepo', $items->getForeignRepo());

        $purchases = $basket->getRelOrError('purchases');
        $this->assertInstanceOf('CL\Purchases\PurchaseRepo', $purchases->getForeignRepo());

        $model = $basket->newModel();

        $this->assertInstanceOf('CL\Purchases\Basket', $model);
    }
}

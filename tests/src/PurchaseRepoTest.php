<?php

namespace CL\Purchases\Test;

use CL\Purchases\PurchaseRepo;

/**
 * @coversDefaultClass CL\Purchases\PurchaseRepo
 */
class PurchaseRepoTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $purchase = new PurchaseRepo();

        $store = $purchase->getRelOrError('store');
        $this->assertInstanceOf('CL\Purchases\StoreRepo', $store->getForeignRepo());

        $basket = $purchase->getRelOrError('basket');
        $this->assertInstanceOf('CL\Purchases\BasketRepo', $basket->getForeignRepo());

        $items = $purchase->getRelOrError('items');
        $this->assertInstanceOf('CL\Purchases\BasketItemRepo', $items->getForeignRepo());

        $model = $purchase->newModel();

        $this->assertInstanceOf('CL\Purchases\Purchase', $model);
    }
}

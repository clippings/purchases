<?php

namespace CL\Purchases\Test;

use CL\Purchases\BasketItemRepo;

/**
 * @coversDefaultClass CL\Purchases\BasketItemRepo
 */
class BasketItemRepoTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $basket = new BasketItemRepo();

        $billing = $basket->getRelOrError('basket');
        $this->assertInstanceOf('CL\Purchases\BasketRepo', $billing->getForeignRepo());

        $items = $basket->getRelOrError('purchase');
        $this->assertInstanceOf('CL\Purchases\PurchaseRepo', $items->getForeignRepo());

        $purchases = $basket->getRelOrError('refundItem');
        $this->assertInstanceOf('CL\Purchases\RefundItemRepo', $purchases->getForeignRepo());

        $model = $basket->newModel();

        $this->assertInstanceOf('CL\Purchases\BasketItem', $model);
    }
}

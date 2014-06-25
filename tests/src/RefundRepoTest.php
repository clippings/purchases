<?php

namespace CL\Purchases\Test;

use CL\Purchases\RefundRepo;

/**
 * @coversDefaultClass CL\Purchases\RefundRepo
 */
class RefundRepoTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $refund = new RefundRepo();

        $purchase = $refund->getRelOrError('purchase');
        $this->assertInstanceOf('CL\Purchases\PurchaseRepo', $purchase->getForeignRepo());

        $items = $refund->getRelOrError('items');
        $this->assertInstanceOf('CL\Purchases\RefundItemRepo', $items->getForeignRepo());

        $model = $refund->newModel();

        $this->assertInstanceOf('CL\Purchases\Refund', $model);
    }
}

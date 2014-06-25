<?php

namespace CL\Purchases\Test;

use CL\Purchases\RefundItemRepo;

/**
 * @coversDefaultClass CL\Purchases\RefundItemRepo
 */
class RefundItemRepoTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $refundItem = new RefundItemRepo();

        $refund = $refundItem->getRelOrError('refund');
        $this->assertInstanceOf('CL\Purchases\RefundRepo', $refund->getForeignRepo());

        $item = $refundItem->getRelOrError('item');
        $this->assertInstanceOf('CL\Purchases\BasketItemRepo', $item->getForeignRepo());

        $model = $refundItem->newModel();

        $this->assertInstanceOf('CL\Purchases\RefundItem', $model);
    }
}

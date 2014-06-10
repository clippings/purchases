<?php

namespace CL\Purchases\Test\Repo;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Repo\Refund;

/**
 * @coversDefaultClass CL\Purchases\Repo\Refund
 */
class RefundTest extends AbstractTestCase
{
    /**
     * @covers ::newInstance
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $refund = Refund::newInstance();

        $basket = $refund->getRelOrError('basket');
        $this->assertInstanceOf('CL\Purchases\Repo\Basket', $basket->getForeignRepo());

        $items = $refund->getRelOrError('items');
        $this->assertInstanceOf('CL\Purchases\Repo\RefundItem', $items->getForeignRepo());

        $model = $refund->newModel();

        $this->assertInstanceOf('CL\Purchases\Model\Refund', $model);
    }
}

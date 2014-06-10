<?php

namespace CL\Purchases\Test\Model;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Model\BasketItem;
use CL\Purchases\Model\Refund;
use CL\Purchases\Model\RefundItem;


/**
 * @coversDefaultClass CL\Purchases\Model\RefundItem
 */
class RefundItemTest extends AbstractTestCase
{
    /**
     * @covers ::getRepo
     */
    public function testGetRepo()
    {
        $item = new RefundItem();

        $repo = $item->getRepo();
        $repo2 = $item->getRepo();

        $this->assertSame($repo, $repo2);
        $this->assertInstanceOf('CL\Purchases\Model\RefundItem', $item);
    }

    /**
     * @covers ::getRefund
     * @covers ::setRefund
     */
    public function testRefund()
    {
        $item = new RefundItem();

        $refund = $item->getRefund();

        $this->assertInstanceOf('CL\Purchases\Model\Refund', $refund);
        $this->assertTrue($refund->isVoid());

        $refund = new Refund();

        $item->setRefund($refund);

        $this->assertSame($refund, $item->getRefund());
    }

    /**
     * @covers ::getItem
     * @covers ::setItem
     */
    public function testItem()
    {
        $refundItem = new RefundItem();

        $item = $refundItem->getItem();

        $this->assertInstanceOf('CL\Purchases\Model\BasketItem', $item);
        $this->assertTrue($item->isVoid());

        $item = new BasketItem();

        $refundItem->setItem($item);

        $this->assertSame($item, $refundItem->getItem());
    }
}

<?php

namespace CL\Purchases\Test;

use CL\Purchases\BasketItem;
use CL\Purchases\Refund;
use CL\Purchases\RefundItem;
use SebastianBergmann\Money\Currency;
use SebastianBergmann\Money\Money;


/**
 * @coversDefaultClass CL\Purchases\RefundItem
 */
class RefundItemTest extends AbstractTestCase
{
    /**
     * @covers ::getRefund
     * @covers ::setRefund
     */
    public function testRefund()
    {
        $item = new RefundItem();

        $refund = $item->getRefund();

        $this->assertInstanceOf('CL\Purchases\Refund', $refund);
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

        $this->assertInstanceOf('CL\Purchases\BasketItem', $item);
        $this->assertTrue($item->isVoid());

        $item = new BasketItem();

        $refundItem->setItem($item);

        $this->assertSame($item, $refundItem->getItem());
    }

    /**
     * @covers ::getName
     */
    public function testName()
    {
        $refundItem = new RefundItem();

        $item = $this->getMock('CL\Purchases\BasketItem', ['getName']);
        $item
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue(32132));

        $refundItem->setItem($item);

        $this->assertEquals(32132, $refundItem->getName());
    }

    /**
     * @covers ::getDescription
     */
    public function testDescription()
    {
        $refundItem = new RefundItem();

        $item = $this->getMock('CL\Purchases\ProductItem', ['getDescription']);
        $item
            ->expects($this->once())
            ->method('getDescription')
            ->will($this->returnValue('Faulty Product !!'));

        $refundItem->setItem($item);

        $this->assertEquals('Refund for Faulty Product !!', $refundItem->getDescription());
    }

    /**
     * @covers ::getCurrency
     */
    public function testCurrency()
    {
        $refundItem = new RefundItem();

        $currency = new Currency('EUR');

        $refund = $this->getMock('CL\Purchases\Refund', ['getCurrency']);
        $refund
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue($currency));

        $refundItem->setRefund($refund);

        $this->assertSame($currency, $refundItem->getCurrency());
    }

    /**
     * @covers ::getSourceValue
     */
    public function testSourceValue()
    {
        $refundItem = new RefundItem();

        $value = new Money(1100, new Currency('EUR'));

        $item = $this->getMock('CL\Purchases\BasketItem', ['getValue']);
        $item
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($value));

        $refundItem->setItem($item);

        $this->assertSame($value, $refundItem->getSourceValue());
    }
}

<?php

namespace CL\Purchases\Test;

use CL\Purchases\OrderItem;
use CL\Purchases\Purchase;
use CL\Purchases\Order;
use SebastianBergmann\Money\Money;
use SebastianBergmann\Money\Currency;

/**
 * @coversDefaultClass CL\Purchases\OrderItem
 */
class OrderItemTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $order = OrderItem::getRepo();

        $billing = $order->getRelOrError('order');
        $this->assertEquals('CL\Purchases\Order', $billing->getRepo()->getModelClass());

        $items = $order->getRelOrError('purchase');
        $this->assertEquals('CL\Purchases\Purchase', $items->getRepo()->getModelClass());
    }

    /**
     * @covers ::getOrder
     * @covers ::setOrder
     */
    public function testOrder()
    {
        $orderItem = new OrderItem();

        $order = $orderItem->getOrder();

        $this->assertInstanceOf('CL\Purchases\Order', $order);
        $this->assertTrue($order->isVoid());

        $order = new Order();

        $orderItem->setOrder($order);

        $this->assertSame($order, $orderItem->getOrder());
    }

    /**
     * @covers ::getPurchase
     * @covers ::setPurchase
     */
    public function testPurchase()
    {
        $orderItem = new OrderItem();

        $purchase = $orderItem->getPurchase();

        $this->assertInstanceOf('CL\Purchases\Purchase', $purchase);
        $this->assertTrue($purchase->isVoid());

        $purchase = new Purchase();

        $orderItem->setPurchase($purchase);

        $this->assertSame($purchase, $orderItem->getPurchase());
    }

    /**
     * @coversNothing
     */
    public function testIntegration()
    {
        $orderItem = OrderItem::find(1);
        $order = Order::find(1);
        $purchase = Purchase::find(1);

        $this->assertSame($order, $orderItem->getOrder());
        $this->assertSame($purchase, $orderItem->getPurchase());
    }

    /**
     * @covers ::getCurrency
     */
    public function testCurrency()
    {
        $item = new OrderItem();

        $currency = new Currency('EUR');

        $order = $this->getMock('CL\Purchases\Order', ['getCurrency']);
        $order
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue($currency));

        $item->setOrder($order);

        $this->assertSame($currency, $item->getCurrency());
    }

    /**
     * @covers ::getSourceValue
     */
    public function testSourceValue()
    {
        $item = new OrderItem(['value' => 1000]);

        $value = new Money(1000, new Currency('GBP'));

        $this->assertEquals($value, $item->getSourceValue());
    }

    /**
     * @covers ::getTotalValue
     */
    public function testTotalValue()
    {
        $item = new OrderItem(['quantity' => 3]);

        $value = new Money(1000, new Currency('EUR'));

        $item = $this->getMock('CL\Purchases\OrderItem', ['getValue'], [['quantity' => 3]]);
        $item
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($value));

        $expected = new Money(3000, new Currency('EUR'));

        $this->assertEquals($expected, $item->getTotalValue());
    }
}

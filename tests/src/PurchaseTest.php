<?php

namespace CL\Purchases\Test;

use CL\Purchases\Store;
use CL\Purchases\Order;
use CL\Purchases\Purchase;
use CL\Purchases\Refund;
use CL\Purchases\OrderItem;
use SebastianBergmann\Money\Currency;

/**
 * @coversDefaultClass CL\Purchases\Purchase
 */
class PurchaseTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $purchase = Purchase::getRepo();

        $store = $purchase->getRelOrError('store');
        $this->assertEquals('CL\Purchases\Store', $store->getRepo()->getModelClass());

        $order = $purchase->getRelOrError('order');
        $this->assertEquals('CL\Purchases\Order', $order->getRepo()->getModelClass());

        $items = $purchase->getRelOrError('items');
        $this->assertEquals('CL\Purchases\OrderItem', $items->getRepo()->getModelClass());
    }

    /**
     * @covers ::getStore
     * @covers ::setStore
     */
    public function testStore()
    {
        $product = new Purchase();

        $store = $product->getStore();

        $this->assertInstanceOf('CL\Purchases\Store', $store);
        $this->assertTrue($store->isVoid());

        $store = new Store();

        $product->setStore($store);

        $this->assertSame($store, $product->getStore());
    }

    /**
     * @covers ::getOrder
     * @covers ::setOrder
     */
    public function testOrder()
    {
        $product = new Purchase();

        $store = $product->getOrder();

        $this->assertInstanceOf('CL\Purchases\Order', $store);
        $this->assertTrue($store->isVoid());

        $store = new Order();

        $product->setOrder($store);

        $this->assertSame($store, $product->getOrder());
    }

    /**
     * @covers ::getItems
     */
    public function testItems()
    {
        $product = new Purchase();

        $items = $product->getItems();

        $this->assertEquals(OrderItem::getRepo(), $items->getRel()->getRepo());
    }

    /**
     * @covers ::getRefunds
     */
    public function testRefunds()
    {
        $product = new Purchase();

        $refunds = $product->getRefunds();

        $this->assertEquals(Refund::getRepo(), $refunds->getRel()->getRepo());
    }

    /**
     * @covers ::getCurrency
     */
    public function testCurrency()
    {
        $purchase = new Purchase();

        $currency = new Currency('EUR');

        $order = $this->getMock('CL\Purchases\Order', ['getCurrency']);
        $order
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue($currency));

        $purchase->setOrder($order);

        $this->assertSame($currency, $purchase->getCurrency());
    }
}

<?php

namespace CL\Purchases\Test;

use CL\Purchases\Store;
use CL\Purchases\Purchase;
use CL\Purchases\StorePurchase;
use CL\Purchases\Refund;
use CL\Purchases\PurchaseItem;
use SebastianBergmann\Money\Currency;

/**
 * @coversDefaultClass CL\Purchases\StorePurchase
 */
class StorePurchaseTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $storePurchase = StorePurchase::getRepo();

        $store = $storePurchase->getRelOrError('store');
        $this->assertEquals('CL\Purchases\Store', $store->getRepo()->getModelClass());

        $purchase = $storePurchase->getRelOrError('purchase');
        $this->assertEquals('CL\Purchases\Purchase', $purchase->getRepo()->getModelClass());

        $items = $storePurchase->getRelOrError('items');
        $this->assertEquals('CL\Purchases\PurchaseItem', $items->getRepo()->getModelClass());
    }

    /**
     * @covers ::getStore
     * @covers ::setStore
     */
    public function testStore()
    {
        $product = new StorePurchase();

        $store = $product->getStore();

        $this->assertInstanceOf('CL\Purchases\Store', $store);
        $this->assertTrue($store->isVoid());

        $store = new Store();

        $product->setStore($store);

        $this->assertSame($store, $product->getStore());
    }

    /**
     * @covers ::getPurchase
     * @covers ::setPurchase
     */
    public function testPurchase()
    {
        $product = new StorePurchase();

        $store = $product->getPurchase();

        $this->assertInstanceOf('CL\Purchases\Purchase', $store);
        $this->assertTrue($store->isVoid());

        $store = new Purchase();

        $product->setPurchase($store);

        $this->assertSame($store, $product->getPurchase());
    }

    /**
     * @covers ::getItems
     */
    public function testItems()
    {
        $product = new StorePurchase();

        $items = $product->getItems();

        $this->assertEquals(PurchaseItem::getRepo(), $items->getRel()->getRepo());
    }

    /**
     * @covers ::getRefunds
     */
    public function testRefunds()
    {
        $product = new StorePurchase();

        $refunds = $product->getRefunds();

        $this->assertEquals(Refund::getRepo(), $refunds->getRel()->getRepo());
    }

    /**
     * @covers ::getCurrency
     */
    public function testCurrency()
    {
        $purchase = new StorePurchase();

        $currency = new Currency('EUR');

        $order = $this->getMock('CL\Purchases\Purchase', ['getCurrency']);
        $order
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue($currency));

        $purchase->setPurchase($order);

        $this->assertSame($currency, $purchase->getCurrency());
    }
}

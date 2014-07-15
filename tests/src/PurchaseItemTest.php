<?php

namespace CL\Purchases\Test;

use CL\Purchases\PurchaseItem;
use CL\Purchases\StorePurchase;
use CL\Purchases\Purchase;
use SebastianBergmann\Money\Money;
use SebastianBergmann\Money\Currency;

/**
 * @coversDefaultClass CL\Purchases\PurchaseItem
 */
class PurchaseItemTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $purchaseItem = PurchaseItem::getRepo();

        $purchase = $purchaseItem->getRelOrError('purchase');
        $this->assertEquals('CL\Purchases\Purchase', $purchase->getRepo()->getModelClass());

        $storePurchase = $purchaseItem->getRelOrError('storePurchase');
        $this->assertEquals('CL\Purchases\StorePurchase', $storePurchase->getRepo()->getModelClass());
    }

    /**
     * @covers ::getPurchase
     * @covers ::setPurchase
     */
    public function testPurchase()
    {
        $purchaseItem = new PurchaseItem();

        $order = $purchaseItem->getPurchase();

        $this->assertInstanceOf('CL\Purchases\Purchase', $order);
        $this->assertTrue($order->isVoid());

        $order = new Purchase();

        $purchaseItem->setPurchase($order);

        $this->assertSame($order, $purchaseItem->getPurchase());
    }

    /**
     * @covers ::getStorePurchase
     * @covers ::setStorePurchase
     */
    public function testStorePurchase()
    {
        $purchaseItem = new PurchaseItem();

        $storePurchase = $purchaseItem->getStorePurchase();

        $this->assertInstanceOf('CL\Purchases\StorePurchase', $storePurchase);
        $this->assertTrue($storePurchase->isVoid());

        $storePurchase = new StorePurchase();

        $purchaseItem->setStorePurchase($storePurchase);

        $this->assertSame($storePurchase, $purchaseItem->getStorePurchase());
    }

    /**
     * @coversNothing
     */
    public function testIntegration()
    {
        $purchaseItem = PurchaseItem::find(1);
        $order = Purchase::find(1);
        $purchase = StorePurchase::find(1);

        $this->assertSame($order, $purchaseItem->getPurchase());
        $this->assertSame($purchase, $purchaseItem->getStorePurchase());
    }

    /**
     * @covers ::getCurrency
     */
    public function testCurrency()
    {
        $item = new PurchaseItem();

        $currency = new Currency('EUR');

        $purchase = $this->getMock('CL\Purchases\Purchase', ['getCurrency']);
        $purchase
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue($currency));

        $item->setPurchase($purchase);

        $this->assertSame($currency, $item->getCurrency());
    }

    /**
     * @covers ::getSourceValue
     */
    public function testSourceValue()
    {
        $item = new PurchaseItem(['value' => 1000]);

        $value = new Money(1000, new Currency('GBP'));

        $this->assertEquals($value, $item->getSourceValue());
    }

    /**
     * @covers ::getTotalValue
     */
    public function testTotalValue()
    {
        $item = new PurchaseItem(['quantity' => 3]);

        $value = new Money(1000, new Currency('EUR'));

        $item = $this->getMock('CL\Purchases\PurchaseItem', ['getValue'], [['quantity' => 3]]);
        $item
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($value));

        $expected = new Money(3000, new Currency('EUR'));

        $this->assertEquals($expected, $item->getTotalValue());
    }
}

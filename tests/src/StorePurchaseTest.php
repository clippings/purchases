<?php

namespace CL\Purchases\Test;

use CL\Purchases\Store;
use CL\Purchases\Purchase;
use CL\Purchases\StorePurchase;
use CL\Purchases\Refund;
use CL\Purchases\PurchaseItem;
use SebastianBergmann\Money\Money;
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
     * @covers ::addPurchaseItem
     */
    public function testAddPurchaseItem()
    {
        $storePurchase = new StorePurchase();
        $purchase = new Purchase();
        $purchaseItem = new PurchaseItem();

        $storePurchase->setPurchase($purchase);

        $storePurchase->addPurchaseItem($purchaseItem);

        $this->assertTrue($storePurchase->getItems()->has($purchaseItem));
        $this->assertTrue($purchase->getItems()->has($purchaseItem));
    }

    /**
     * @covers ::getPurchase
     * @covers ::setPurchase
     */
    public function testPurchase()
    {
        $storePurchase = new StorePurchase();

        $purchase = $storePurchase->getPurchase();

        $this->assertInstanceOf('CL\Purchases\Purchase', $purchase);
        $this->assertTrue($purchase->isVoid());

        $purchase = new Purchase();

        $storePurchase->setPurchase($purchase);

        $this->assertSame($purchase, $storePurchase->getPurchase());
    }

    /**
     * @covers ::getItems
     */
    public function testItems()
    {
        $storePurchase = new StorePurchase();

        $items = $storePurchase->getItems();

        $this->assertEquals(PurchaseItem::getRepo(), $items->getRel()->getRepo());
    }

    /**
     * @covers ::getRefunds
     */
    public function testRefunds()
    {
        $storePurchase = new StorePurchase();

        $refunds = $storePurchase->getRefunds();

        $this->assertEquals(Refund::getRepo(), $refunds->getRel()->getRepo());
    }

    /**
     * @covers ::getRefundsValue
     */
    public function testGetRefundsValue()
    {
        $storePurchase = new StorePurchase();

        $storePurchase->getRefunds()
            ->add(new Refund(['value' => 100]))
            ->add(new Refund(['value' => 1000]))
            ->add(new Refund(['value' => 300]));

        $this->assertEquals(new Money(1400, $storePurchase->getCurrency()), $storePurchase->getRefundsValue());
    }

    public function dataGetRemainingValue()
    {
        return [
            [1000, 800, 200],
            [1000, 200, 800],
        ];
    }

    /**
     * @dataProvider dataGetRemainingValue
     * @covers ::getRemainingValue
     */
    public function testGetRemainingValue($value, $refundsValue, $expected)
    {
        $storePurchase = $this->getMock('CL\Purchases\StorePurchase', ['getRefundsValue'], [['value' => $value, 'isFrozen' => true]]);

        $storePurchase
            ->expects($this->once())
            ->method('getRefundsValue')
            ->will($this->returnValue(new Money($refundsValue, $storePurchase->getCurrency())));

        $this->assertEquals(new Money($expected, $storePurchase->getCurrency()), $storePurchase->getRemainingValue());
    }

    /**
     * @covers ::isFullyRefunded
     */
    public function testIsFullyRefunded()
    {
        $storePurchase = new StorePurchase(['value' => 1000, 'isFrozen' => true]);

        $this->assertFalse($storePurchase->isFullyRefunded());

        $storePurchase->getRefunds()->add(new Refund(['value' => 100]));

        $this->assertFalse($storePurchase->isFullyRefunded());

        $storePurchase->getRefunds()->add(new Refund(['value' => 900]));

        $this->assertTrue($storePurchase->isFullyRefunded());
    }

    /**
     * @covers ::getCurrency
     */
    public function testCurrency()
    {
        $storePurchase = new StorePurchase();

        $currency = new Currency('EUR');

        $order = $this->getMock('CL\Purchases\Purchase', ['getCurrency']);
        $order
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue($currency));

        $storePurchase->setPurchase($order);

        $this->assertSame($currency, $storePurchase->getCurrency());
    }
}

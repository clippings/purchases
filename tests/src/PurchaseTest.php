<?php

namespace CL\Purchases\Test;

use CL\Purchases\Store;
use CL\Purchases\Basket;
use CL\Purchases\Purchase;
use CL\Purchases\BasketItemRepo;
use CL\Purchases\RefundRepo;
use SebastianBergmann\Money\Currency;

/**
 * @coversDefaultClass CL\Purchases\Purchase
 */
class PurchaseTest extends AbstractTestCase
{
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
     * @covers ::getBasket
     * @covers ::setBasket
     */
    public function testBasket()
    {
        $product = new Purchase();

        $store = $product->getBasket();

        $this->assertInstanceOf('CL\Purchases\Basket', $store);
        $this->assertTrue($store->isVoid());

        $store = new Basket();

        $product->setBasket($store);

        $this->assertSame($store, $product->getBasket());
    }

    /**
     * @covers ::getItems
     */
    public function testItems()
    {
        $product = new Purchase();

        $items = $product->getItems();

        $this->assertEquals(BasketItemRepo::get(), $items->getRel()->getForeignRepo());
    }

    /**
     * @covers ::getRefunds
     */
    public function testRefunds()
    {
        $product = new Purchase();

        $refunds = $product->getRefunds();

        $this->assertEquals(RefundRepo::get(), $refunds->getRel()->getForeignRepo());
    }

    /**
     * @covers ::getCurrency
     */
    public function testCurrency()
    {
        $purchase = new Purchase();

        $currency = new Currency('EUR');

        $basket = $this->getMock('CL\Purchases\Basket', ['getCurrency']);
        $basket
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue($currency));

        $purchase->setBasket($basket);

        $this->assertSame($currency, $purchase->getCurrency());
    }
}

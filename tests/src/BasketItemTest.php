<?php

namespace CL\Purchases\Test;

use CL\Purchases\BasketItem;
use CL\Purchases\Purchase;
use CL\Purchases\Basket;
use SebastianBergmann\Money\Money;
use SebastianBergmann\Money\Currency;

/**
 * @coversDefaultClass CL\Purchases\BasketItem
 */
class BasketItemTest extends AbstractTestCase
{
    /**
     * @covers ::getBasket
     * @covers ::setBasket
     */
    public function testBasket()
    {
        $basketItem = new BasketItem();

        $basket = $basketItem->getBasket();

        $this->assertInstanceOf('CL\Purchases\Basket', $basket);
        $this->assertTrue($basket->isVoid());

        $basket = new Basket();

        $basketItem->setBasket($basket);

        $this->assertSame($basket, $basketItem->getBasket());
    }

    /**
     * @covers ::getPurchase
     * @covers ::setPurchase
     */
    public function testPurchase()
    {
        $basketItem = new BasketItem();

        $purchase = $basketItem->getPurchase();

        $this->assertInstanceOf('CL\Purchases\Purchase', $purchase);
        $this->assertTrue($purchase->isVoid());

        $purchase = new Purchase();

        $basketItem->setPurchase($purchase);

        $this->assertSame($purchase, $basketItem->getPurchase());
    }

    /**
     * @coversNothing
     */
    public function testIntegration()
    {
        $basketItem = BasketItem::find(1);
        $basket = Basket::find(1);
        $purchase = Purchase::find(1);

        $this->assertSame($basket, $basketItem->getBasket());
        $this->assertSame($purchase, $basketItem->getPurchase());
    }

    /**
     * @covers ::getCurrency
     */
    public function testCurrency()
    {
        $item = new BasketItem();

        $currency = new Currency('EUR');

        $basket = $this->getMock('CL\Purchases\Basket', ['getCurrency']);
        $basket
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue($currency));

        $item->setBasket($basket);

        $this->assertSame($currency, $item->getCurrency());
    }

    /**
     * @covers ::getSourceValue
     */
    public function testSourceValue()
    {
        $item = new BasketItem(['value' => 1000]);

        $value = new Money(1000, new Currency('GBP'));

        $this->assertEquals($value, $item->getSourceValue());
    }

    /**
     * @covers ::getTotalValue
     */
    public function testTotalValue()
    {
        $item = new BasketItem(['quantity' => 3]);

        $value = new Money(1000, new Currency('EUR'));

        $item = $this->getMock('CL\Purchases\BasketItem', ['getValue'], [['quantity' => 3]]);
        $item
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($value));

        $expected = new Money(3000, new Currency('EUR'));

        $this->assertEquals($expected, $item->getTotalValue());
    }
}

<?php

namespace CL\Purchases\Test\Model;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Model\BasketItem;
use CL\Purchases\Model\Purchase;
use CL\Purchases\Model\Basket;
use CL\Purchases\Repo;
use SebastianBergmann\Money\Money;
use SebastianBergmann\Money\Currency;

/**
 * @coversDefaultClass CL\Purchases\Model\BasketItem
 */
class BasketItemTest extends AbstractTestCase
{
    /**
     * @covers ::getRepo
     */
    public function testGetRepo()
    {
        $basketItem = new BasketItem();

        $repo = $basketItem->getRepo();
        $repo2 = $basketItem->getRepo();

        $this->assertSame($repo, $repo2);
        $this->assertInstanceOf('CL\Purchases\Model\BasketItem', $basketItem);
    }

    /**
     * @covers ::getBasket
     * @covers ::setBasket
     */
    public function testBasket()
    {
        $basketItem = new BasketItem();

        $basket = $basketItem->getBasket();

        $this->assertInstanceOf('CL\Purchases\Model\Basket', $basket);
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

        $this->assertInstanceOf('CL\Purchases\Model\Purchase', $purchase);
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
        $basketItem = Repo\BasketItem::get()->find(1);
        $basket = Repo\Basket::get()->find(1);
        $purchase = Repo\Purchase::get()->find(1);

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

        $basket = $this->getMock('CL\Purchases\Model\Basket', ['getCurrency']);
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

        $item = $this->getMock('CL\Purchases\Model\BasketItem', ['getValue'], [['quantity' => 3]]);
        $item
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($value));

        $expected = new Money(3000, new Currency('EUR'));

        $this->assertEquals($expected, $item->getTotalValue());
    }
}

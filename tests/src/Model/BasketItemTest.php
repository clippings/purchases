<?php

namespace CL\Purchases\Test\Model;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Model\BasketItem;
use CL\Purchases\Model\Purchase;
use CL\Purchases\Model\Basket;
use CL\Purchases\Repo;
use SebastianBergmann\Money\EUR;
use SebastianBergmann\Money\GBP;


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
     * @covers ::getPrice
     * @covers ::setPrice
     */
    public function testPrice()
    {
        $basketItem = new BasketItem(['price' => 150]);
        $basket = new Basket(['currency' => 'EUR']);

        $basketItem->setBasket($basket);

        $this->assertTrue($basketItem->getPrice()->equals(new EUR(150)));
    }

    public function testFreeze()
    {
        $basketItem = $this->getMock('CL\Purchases\Model\BasketItem', ['getPrice'], [['price' => 150]]);

        $basketItem
            ->expects($this->once())
            ->method('getPrice')
            ->will($this->returnValue(new GBP(2000)));

        $this->assertEquals(150, $basketItem->price);

        $basketItem->freeze();

        $this->assertEquals(true, $basketItem->isFrozen);

        $this->assertEquals(2000, $basketItem->price);

        $basketItem->unfreeze();

        $this->assertEquals(false, $basketItem->isFrozen);
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
}

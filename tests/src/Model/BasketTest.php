<?php

namespace CL\Purchases\Test\Model;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Model\Basket;
use CL\Purchases\Model\ProductItem;
use CL\Purchases\Repo;
use SebastianBergmann\Money\GBP;


/**
 * @coversDefaultClass CL\Purchases\Model\Basket
 */
class BasketTest extends AbstractTestCase
{
    /**
     * @covers ::getRepo
     */
    public function testGetRepo()
    {
        $basket = new Basket();

        $repo = $basket->getRepo();
        $repo2 = $basket->getRepo();

        $this->assertSame($repo, $repo2);
        $this->assertInstanceOf('CL\Purchases\Model\Basket', $basket);
    }

    /**
     * @covers ::getPurchases
     */
    public function testPurchases()
    {
        $basket = new Basket();

        $purchases = $basket->getPurchases();

        $this->assertInstanceOf('CL\Purchases\Collection\Purchases', $purchases);
    }

    /**
     * @covers ::getItems
     */
    public function testItems()
    {
        $basket = new Basket();

        $items = $basket->getItems();

        $this->assertInstanceOf('CL\Purchases\Collection\BasketItems', $items);
    }

    /**
     * @covers ::getTotal
     * @covers ::getProductTotal
     * @covers ::getRefundTotal
     */
    public function testTotal()
    {
        $basket = Repo\Basket::get()->find(1);

        $this->assertTrue($basket->getTotal()->equals(new GBP(6000)));
        $this->assertTrue($basket->getProductTotal()->equals(new GBP(10000)));
        $this->assertTrue($basket->getRefundTotal()->equals(new GBP(-4000)));
    }

    /**
     * @covers ::isPaid
     */
    public function testIsPaid()
    {
        $basket = new Basket(['status' => Basket::PAID]);

        $this->assertTrue($basket->isPaid());
        $this->assertFalse($basket->isPaymentPending());
    }

    /**
     * @covers ::isPaymentPending
     */
    public function testIsPending()
    {
        $basket = new Basket(['status' => Basket::PAYMENT_PENDING]);

        $this->assertFalse($basket->isPaid());
        $this->assertTrue($basket->isPaymentPending());
    }

    /**
     * @covers ::addProduct
     */
    public function testAddProduct()
    {
        $basket = Repo\Basket::get()->find(2);
        $product1 = Repo\Product::get()->find(1);
        $product2 = Repo\Product::get()->find(5);

        $basket
            ->addProduct($product1)
            ->addProduct($product1, 4)
            ->addProduct($product2);

        Repo\Basket::get()->save($basket);

        $item1 = $basket->getItems()->getFirst();
        $this->assertSame($product1, $item1->getProduct());
        $this->assertEquals(5, $item1->quantity);

        $item2 = $basket->getItems()->getNext();
        $this->assertSame($product2, $item2->getProduct());
        $this->assertEquals(1, $item2->quantity);

        $this->assertCount(2, $basket->getItems());
        $this->assertCount(1, $basket->getPurchases());

        $this->assertEquals($product1->getStore(), $basket->getPurchases()->getFirst()->getStore());
        $this->assertTrue($basket->getPurchases()->getFirst()->getBasketItems()->has($item1));
        $this->assertTrue($basket->getPurchases()->getFirst()->getBasketItems()->has($item2));
    }

    /**
     * @covers ::freeze
     * @covers ::unfreeze
     */
    public function testFreeze()
    {
        $basket = Repo\Basket::get()->find(1);

        $item1 = $basket->getItems()->getFirst();
        $item2 = $basket->getItems()->getNext();
        $item3 = $basket->getItems()->getNext();

        $this->assertEquals(true, $item1->isFrozen);
        $this->assertEquals(true, $item2->isFrozen);
        $this->assertEquals(true, $item3->isFrozen);

        $basket->unfreeze();

        $this->assertEquals(false, $item1->isFrozen);
        $this->assertTrue($item1->getPrice()->equals(new GBP(1000)));

        $this->assertEquals(false, $item2->isFrozen);
        $this->assertTrue($item2->getPrice()->equals(new GBP(2000)));

        $this->assertEquals(false, $item3->isFrozen);
        $this->assertTrue($item3->getPrice()->equals(new GBP(3000)));

        $item1->getProduct()->setPrice(new GBP(20000));

        $basket->freeze();

        $this->assertEquals(true, $item1->isFrozen);
        $this->assertTrue($item1->getPrice()->equals(new GBP(20000)));

        $this->assertEquals(true, $item2->isFrozen);
        $this->assertEquals(true, $item3->isFrozen);
    }

    /**
     * @coversNothing
     */
    public function testIntegration()
    {
        $basket = Repo\Basket::get()->find(1);

        $item1 = Repo\BasketItem::get()->find(1);
        $item2 = Repo\BasketItem::get()->find(2);
        $item3 = Repo\BasketItem::get()->find(3);
        $item4 = Repo\BasketItem::get()->find(4);
        $item5 = Repo\BasketItem::get()->find(5);
        $purchase1 = Repo\Purchase::get()->find(1);
        $purchase2 = Repo\Purchase::get()->find(2);
        $billing = Repo\Address::get()->find(1);

        $items = $basket->getItems();

        $this->assertSame([$item1, $item2, $item3, $item4, $item5], $basket->getItems()->toArray());
        $this->assertSame([$purchase1, $purchase2], $basket->getPurchases()->toArray());
        $this->assertSame($billing, $basket->getBilling());
    }
}

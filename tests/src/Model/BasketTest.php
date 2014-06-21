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

        $this->assertContainsOnlyInstancesOf('CL\Purchases\Model\Purchase', $purchases->toArray());
    }

    /**
     * @covers ::getItems
     */
    public function testItems()
    {
        $basket = new Basket();

        $items = $basket->getItems();

        $this->assertContainsOnlyInstancesOf('CL\Purchases\Model\BasketItem', $items->toArray());
    }

    /**
     * @covers ::performFreeze
     */
    public function testPerformFreeze()
    {
        $basket = new Basket();

        $purchase1 = $this->getMock('CL\Purchases\Model\Purchase', ['freeze']);
        $purchase1
            ->expects($this->once())
            ->method('freeze');

        $purchase2 = $this->getMock('CL\Purchases\Model\Purchase', ['freeze']);
        $purchase2
            ->expects($this->once())
            ->method('freeze');

        $basket
            ->getPurchases()
                ->add($purchase1)
                ->add($purchase2);

        $basket->performFreeze();
    }

    /**
     * @covers ::performUnfreeze
     */
    public function testPerformUnfreeze()
    {
        $basket = new Basket();

        $purchase1 = $this->getMock('CL\Purchases\Model\Purchase', ['unfreeze']);
        $purchase1
            ->expects($this->once())
            ->method('unfreeze');

        $purchase2 = $this->getMock('CL\Purchases\Model\Purchase', ['unfreeze']);
        $purchase2
            ->expects($this->once())
            ->method('unfreeze');

        $basket
            ->getPurchases()
                ->add($purchase1)
                ->add($purchase2);

        $basket->performUnfreeze();
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
        $this->assertInstanceOf('CL\Purchases\Model\ProductItem', $item1);
        $this->assertSame($product1, $item1->getProduct());
        $this->assertEquals(5, $item1->quantity);

        $item2 = $basket->getItems()->getNext();
        $this->assertInstanceOf('CL\Purchases\Model\ProductItem', $item2);
        $this->assertSame($product2, $item2->getProduct());
        $this->assertEquals(1, $item2->quantity);

        $this->assertCount(2, $basket->getItems());
        $this->assertCount(1, $basket->getPurchases());

        $this->assertEquals($product1->getStore(), $basket->getPurchases()->getFirst()->getStore());
        $this->assertTrue($basket->getPurchases()->getFirst()->getItems()->has($item1));
        $this->assertTrue($basket->getPurchases()->getFirst()->getItems()->has($item2));
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

        $purchase1 = Repo\Purchase::get()->find(1);
        $purchase2 = Repo\Purchase::get()->find(2);
        $billing = Repo\Address::get()->find(1);

        $items = $basket->getItems();

        $this->assertSame([$item1, $item2, $item3, $item4], $basket->getItems()->toArray());
        $this->assertSame([$purchase1, $purchase2], $basket->getPurchases()->toArray());
        $this->assertSame($billing, $basket->getBilling());
    }

    public function testGetRequestParameters()
    {
        $basket  = Repo\Basket::get()->find(1);

        $data = $basket->getRequestParameters(array());

        $expected = array(
            'items' => [
                [
                    'name' => 1,
                    'description' => 'Product 1',
                    'price' => 10.0,
                    'quantity' => 1,
                ],
                [
                    'name' => 2,
                    'description' => 'Product 2',
                    'price' => 20.0,
                    'quantity' => 1,
                ],
                [
                    'name' => 3,
                    'description' => 'Product 3',
                    'price' => 30.0,
                    'quantity' => 1,
                ],
                [
                    'name' => 4,
                    'description' => 'Product 4',
                    'price' => 40.0,
                    'quantity' => 1,
                ],
            ],
            'amount' => 100.0,
            'currency' => 'GBP',
            'transactionReference' => 1,
            'card' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'address1' => 'Moskovska',
                'address2' => '132',
                'city' => 'Sofia',
                'country' => 'BG',
                'postcode' => '1000',
                'phone' => '123123',
                'email' => 'john@example.com',
            ],
        );

        $this->assertSame($expected, $data);
    }
}

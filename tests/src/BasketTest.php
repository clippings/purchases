<?php

namespace CL\Purchases\Test;

use CL\Purchases\Address;
use CL\Purchases\BasketItem;
use CL\Purchases\Basket;
use CL\Purchases\Store;
use CL\Purchases\Purchase;
use CL\Purchases\Product;
use Omnipay\Omnipay;

/**
 * @coversDefaultClass CL\Purchases\Basket
 */
class BasketTest extends AbstractTestCase
{
    /**
     * @covers ::getBilling
     * @covers ::setBilling
     */
    public function testBilling()
    {
        $item = new Basket();

        $billing = $item->getBilling();

        $this->assertInstanceOf('CL\Purchases\Address', $billing);
        $this->assertTrue($billing->isVoid());

        $billing = new Address();

        $item->setBilling($billing);

        $this->assertSame($billing, $item->getBilling());
    }

    /**
     * @covers ::getPurchases
     */
    public function testPurchases()
    {
        $basket = new Basket();

        $purchases = $basket->getPurchases();

        $this->assertContainsOnlyInstancesOf('CL\Purchases\Purchase', $purchases->toArray());
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
     * @covers ::getProductItems
     */
    public function testProductItems()
    {
        $basket = Basket::find(1);

        $items = BasketItem::findAll()->whereIn('id', [1, 2, 3, 4])->load();

        $productItems = $basket->getProductItems();

        $this->assertSame($items->toArray(), $productItems->toArray());
    }

    /**
     * @covers ::performFreeze
     */
    public function testPerformFreeze()
    {
        $basket = new Basket();

        $purchase1 = $this->getMock('CL\Purchases\Purchase', ['freeze']);
        $purchase1
            ->expects($this->once())
            ->method('freeze');

        $purchase2 = $this->getMock('CL\Purchases\Purchase', ['freeze']);
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

        $purchase1 = $this->getMock('CL\Purchases\Purchase', ['unfreeze']);
        $purchase1
            ->expects($this->once())
            ->method('unfreeze');

        $purchase2 = $this->getMock('CL\Purchases\Purchase', ['unfreeze']);
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
        $basket = Basket::find(2);
        $product1 = Product::find(1);
        $product2 = Product::find(5);

        $basket
            ->addProduct($product1)
            ->addProduct($product1, 4)
            ->addProduct($product2);

        Basket::save($basket);

        $item1 = $basket->getItems()->getFirst();
        $this->assertInstanceOf('CL\Purchases\ProductItem', $item1);
        $this->assertSame($product1, $item1->getProduct());
        $this->assertEquals(5, $item1->quantity);

        $item2 = $basket->getItems()->getNext();
        $this->assertInstanceOf('CL\Purchases\ProductItem', $item2);
        $this->assertSame($product2, $item2->getProduct());
        $this->assertEquals(1, $item2->quantity);

        $this->assertCount(2, $basket->getItems());
        $this->assertCount(1, $basket->getPurchases());

        $this->assertEquals($product1->getStore(), $basket->getPurchases()->getFirst()->getStore());
        $this->assertTrue($basket->getPurchases()->getFirst()->getItems()->has($item1));
        $this->assertTrue($basket->getPurchases()->getFirst()->getItems()->has($item2));
    }

    /**
     * @covers ::getPurchaseForStore
     */
    public function testGetPurchaseForStore()
    {
        $basket = Basket::find(2);
        $store = Store::find(1);

        $purchase = $basket->getPurchaseForStore($store);

        $this->assertInstanceOf('CL\Purchases\Purchase', $purchase);
        $this->assertSame($store, $purchase->getStore());
        $this->assertSame($basket, $purchase->getBasket());
        $this->assertTrue($basket->getPurchases()->has($purchase));

        $purchase2 = $basket->getPurchaseForStore($store);
        $this->assertSame($purchase2, $purchase);
    }

    /**
     * @coversNothing
     */
    public function testIntegration()
    {
        $basket = Basket::find(1);

        $item1 = BasketItem::find(1);
        $item2 = BasketItem::find(2);
        $item3 = BasketItem::find(3);
        $item4 = BasketItem::find(4);

        $purchase1 = Purchase::find(1);
        $purchase2 = Purchase::find(2);
        $billing = Address::find(1);

        $items = $basket->getItems();

        $this->assertSame([$item1, $item2, $item3, $item4], $basket->getItems()->toArray());
        $this->assertSame([$purchase1, $purchase2], $basket->getPurchases()->toArray());
        $this->assertSame($billing, $basket->getBilling());
    }

    /**
     * @covers ::getRequestParameters
     */
    public function testGetRequestParameters()
    {
        $basket  = Basket::find(1);

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

    /**
     * @covers ::purchase
     */
    public function testPurchase()
    {
        $gateway = Omnipay::getFactory()->create('Dummy');

        $basket = $this->getMock('CL\Purchases\Basket', ['execute']);

        $params = ['test', 'test2'];

        $response = 'result response';

        $basket
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->identicalTo($gateway),
                $this->equalTo('purchase'),
                $this->equalTo($params)
            )
            ->will($this->returnValue($response));

        $result = $basket->purchase($gateway, $params);

        $this->assertEquals($response, $result);
    }

    /**
     * @covers ::complete
     */
    public function testComplete()
    {
        $gateway = Omnipay::getFactory()->create('Dummy');

        $basket = $this->getMock('CL\Purchases\Basket', ['execute']);

        $params = ['test', 'test2'];

        $response = 'result response';

        $basket
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->identicalTo($gateway),
                $this->equalTo('complete'),
                $this->equalTo($params)
            )
            ->will($this->returnValue($response));

        $result = $basket->complete($gateway, $params);

        $this->assertEquals($response, $result);
    }
}

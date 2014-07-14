<?php

namespace CL\Purchases\Test;

use CL\Purchases\Address;
use CL\Purchases\OrderItem;
use CL\Purchases\Order;
use CL\Purchases\Store;
use CL\Purchases\Purchase;
use CL\Purchases\Product;
use Omnipay\Omnipay;

/**
 * @coversDefaultClass CL\Purchases\Order
 */
class OrderTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $order = Order::getRepo();

        $billing = $order->getRelOrError('billing');
        $this->assertEquals('CL\Purchases\Address', $billing->getRepo()->getModelClass());

        $items = $order->getRelOrError('items');
        $this->assertEquals('CL\Purchases\OrderItem', $items->getRepo()->getModelClass());

        $purchases = $order->getRelOrError('purchases');
        $this->assertEquals('CL\Purchases\Purchase', $purchases->getRepo()->getModelClass());
    }

    /**
     * @covers ::getBilling
     * @covers ::setBilling
     */
    public function testBilling()
    {
        $item = new Order();

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
        $order = new Order();

        $purchases = $order->getPurchases();

        $this->assertContainsOnlyInstancesOf('CL\Purchases\Purchase', $purchases->toArray());
    }

    /**
     * @covers ::getItems
     */
    public function testItems()
    {
        $order = new Order();

        $items = $order->getItems();

        $this->assertContainsOnlyInstancesOf('CL\Purchases\Model\OrderItem', $items->toArray());
    }

    /**
     * @covers ::getProductItems
     */
    public function testProductItems()
    {
        $order = Order::find(1);

        $items = OrderItem::findAll()->whereIn('id', [1, 2, 3, 4])->load();

        $productItems = $order->getProductItems();

        $this->assertSame($items->toArray(), $productItems->toArray());
    }

    /**
     * @covers ::performFreeze
     * @covers ::freezePurchases
     */
    public function testPerformFreeze()
    {
        $order = new Order();

        $purchase1 = $this->getMock('CL\Purchases\Purchase', ['freeze']);
        $purchase1
            ->expects($this->once())
            ->method('freeze');

        $purchase2 = $this->getMock('CL\Purchases\Purchase', ['freeze']);
        $purchase2
            ->expects($this->once())
            ->method('freeze');

        $order
            ->getPurchases()
                ->add($purchase1)
                ->add($purchase2);

        $order->performFreeze();
    }

    /**
     * @covers ::performUnfreeze
     * @covers ::unfreezePurchases
     */
    public function testPerformUnfreeze()
    {
        $order = new Order();

        $purchase1 = $this->getMock('CL\Purchases\Purchase', ['unfreeze']);
        $purchase1
            ->expects($this->once())
            ->method('unfreeze');

        $purchase2 = $this->getMock('CL\Purchases\Purchase', ['unfreeze']);
        $purchase2
            ->expects($this->once())
            ->method('unfreeze');

        $order
            ->getPurchases()
                ->add($purchase1)
                ->add($purchase2);

        $order->performUnfreeze();
    }

    /**
     * @covers ::addProduct
     */
    public function testAddProduct()
    {
        $order = Order::find(2);
        $product1 = Product::find(1);
        $product2 = Product::find(5);

        $order
            ->addProduct($product1)
            ->addProduct($product1, 4)
            ->addProduct($product2);

        Order::save($order);

        $item1 = $order->getItems()->getFirst();
        $this->assertInstanceOf('CL\Purchases\ProductItem', $item1);
        $this->assertSame($product1, $item1->getProduct());
        $this->assertEquals(5, $item1->quantity);

        $item2 = $order->getItems()->getNext();
        $this->assertInstanceOf('CL\Purchases\ProductItem', $item2);
        $this->assertSame($product2, $item2->getProduct());
        $this->assertEquals(1, $item2->quantity);

        $this->assertCount(2, $order->getItems());
        $this->assertCount(1, $order->getPurchases());

        $this->assertEquals($product1->getStore(), $order->getPurchases()->getFirst()->getStore());
        $this->assertTrue($order->getPurchases()->getFirst()->getItems()->has($item1));
        $this->assertTrue($order->getPurchases()->getFirst()->getItems()->has($item2));
    }

    /**
     * @covers ::getPurchaseForStore
     */
    public function testGetPurchaseForStore()
    {
        $order = Order::find(2);
        $store = Store::find(1);

        $purchase = $order->getPurchaseForStore($store);

        $this->assertInstanceOf('CL\Purchases\Purchase', $purchase);
        $this->assertSame($store, $purchase->getStore());
        $this->assertSame($order, $purchase->getOrder());
        $this->assertTrue($order->getPurchases()->has($purchase));

        $purchase2 = $order->getPurchaseForStore($store);
        $this->assertSame($purchase2, $purchase);
    }

    /**
     * @coversNothing
     */
    public function testIntegration()
    {
        $order = Order::find(1);

        $item1 = OrderItem::find(1);
        $item2 = OrderItem::find(2);
        $item3 = OrderItem::find(3);
        $item4 = OrderItem::find(4);

        $purchase1 = Purchase::find(1);
        $purchase2 = Purchase::find(2);
        $billing = Address::find(1);

        $items = $order->getItems();

        $this->assertSame([$item1, $item2, $item3, $item4], $order->getItems()->toArray());
        $this->assertSame([$purchase1, $purchase2], $order->getPurchases()->toArray());
        $this->assertSame($billing, $order->getBilling());
    }

    /**
     * @covers ::getRequestParameters
     */
    public function testGetRequestParameters()
    {
        $order  = Order::find(1);

        $data = $order->getRequestParameters(array());

        $expected = array(
            'amount' => 100.0,
            'currency' => 'GBP',
            'transactionReference' => 1,
            'items' => [
                0 => [
                    'name' => 1,
                    'description' => 'Items from Store 1',
                    'price' => 60.0,
                    'quantity' => 1,
                ],
                1 => [
                    'name' => 2,
                    'description' => 'Items from Store 1',
                    'price' => 40.0,
                    'quantity' => 1,
                ],
            ],
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

        $order = $this->getMock('CL\Purchases\Order', ['execute']);

        $params = ['test', 'test2'];

        $response = 'result response';

        $order
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->identicalTo($gateway),
                $this->equalTo('purchase'),
                $this->equalTo($params)
            )
            ->will($this->returnValue($response));

        $result = $order->purchase($gateway, $params);

        $this->assertEquals($response, $result);
    }

    /**
     * @covers ::complete
     */
    public function testComplete()
    {
        $gateway = Omnipay::getFactory()->create('Dummy');

        $order = $this->getMock('CL\Purchases\Order', ['execute']);

        $params = ['test', 'test2'];

        $response = 'result response';

        $order
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->identicalTo($gateway),
                $this->equalTo('complete'),
                $this->equalTo($params)
            )
            ->will($this->returnValue($response));

        $result = $order->complete($gateway, $params);

        $this->assertEquals($response, $result);
    }
}

<?php

namespace CL\Purchases\Test;

use CL\Purchases\Address;
use CL\Purchases\PurchaseItem;
use CL\Purchases\Purchase;
use CL\Purchases\Store;
use CL\Purchases\StorePurchase;
use CL\Purchases\Product;
use Omnipay\Omnipay;
use SebastianBergmann\Money\Money;

/**
 * @coversDefaultClass CL\Purchases\Purchase
 */
class PurchaseTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $purchase = Purchase::getRepo();

        $billing = $purchase->getRelOrError('billing');
        $this->assertEquals('CL\Purchases\Address', $billing->getRepo()->getModelClass());

        $items = $purchase->getRelOrError('items');
        $this->assertEquals('CL\Purchases\PurchaseItem', $items->getRepo()->getModelClass());

        $storePurchases = $purchase->getRelOrError('storePurchases');
        $this->assertEquals('CL\Purchases\StorePurchase', $storePurchases->getRepo()->getModelClass());
    }

    /**
     * @covers ::getBilling
     * @covers ::setBilling
     */
    public function testBilling()
    {
        $item = new Purchase();

        $billing = $item->getBilling();

        $this->assertInstanceOf('CL\Purchases\Address', $billing);
        $this->assertTrue($billing->isVoid());

        $billing = new Address();

        $item->setBilling($billing);

        $this->assertSame($billing, $item->getBilling());
    }

    /**
     * @covers ::getStorePurchases
     */
    public function testStorePurchases()
    {
        $purchase = new Purchase();

        $purchases = $purchase->getStorePurchases();

        $this->assertContainsOnlyInstancesOf('CL\Purchases\StorePurchase', $purchases->toArray());
    }

    /**
     * @covers ::getItems
     */
    public function testItems()
    {
        $purchase = new Purchase();

        $items = $purchase->getItems();

        $this->assertContainsOnlyInstancesOf('CL\Purchases\PurchaseItem', $items->toArray());
    }

    /**
     * @covers ::getProductItems
     */
    public function testGetProductItems()
    {
        $purchase = Purchase::find(1);

        $items = PurchaseItem::findAll()->whereIn('id', [1, 2, 3, 4])->load();

        $productItems = $purchase->getProductItems();

        $this->assertSame($items->toArray(), $productItems->toArray());
    }

    /**
     * @covers ::getProductItemsValue
     */
    public function testGetProductItemsValue()
    {
        $purchase = Purchase::find(1);

        $this->assertEquals(new Money(10000, $purchase->getCurrency()), $purchase->getProductItemsValue());
    }

    /**
     * @covers ::performFreeze
     * @covers ::freezeStorePurchases
     */
    public function testPerformFreeze()
    {
        $purchase = new Purchase();

        $purchase1 = $this->getMock('CL\Purchases\StorePurchase', ['freeze']);
        $purchase1
            ->expects($this->once())
            ->method('freeze');

        $purchase2 = $this->getMock('CL\Purchases\StorePurchase', ['freeze']);
        $purchase2
            ->expects($this->once())
            ->method('freeze');

        $purchase
            ->getStorePurchases()
                ->add($purchase1)
                ->add($purchase2);

        $purchase->performFreeze();
    }

    /**
     * @covers ::performUnfreeze
     * @covers ::unfreezeStorePurchases
     */
    public function testPerformUnfreeze()
    {
        $purchase = new Purchase();

        $purchase1 = $this->getMock('CL\Purchases\StorePurchase', ['unfreeze']);
        $purchase1
            ->expects($this->once())
            ->method('unfreeze');

        $purchase2 = $this->getMock('CL\Purchases\StorePurchase', ['unfreeze']);
        $purchase2
            ->expects($this->once())
            ->method('unfreeze');

        $purchase
            ->getStorePurchases()
                ->add($purchase1)
                ->add($purchase2);

        $purchase->performUnfreeze();
    }

    /**
     * @covers ::addProduct
     */
    public function testAddProduct()
    {
        $purchase = Purchase::find(2);
        $product1 = Product::find(1);
        $product2 = Product::find(5);

        $purchase
            ->addProduct($product1)
            ->addProduct($product1, 4)
            ->addProduct($product2);

        Purchase::save($purchase);

        $item1 = $purchase->getItems()->getFirst();
        $this->assertInstanceOf('CL\Purchases\ProductItem', $item1);
        $this->assertSame($product1, $item1->getProduct());
        $this->assertEquals(5, $item1->quantity);

        $item2 = $purchase->getItems()->getNext();
        $this->assertInstanceOf('CL\Purchases\ProductItem', $item2);
        $this->assertSame($product2, $item2->getProduct());
        $this->assertEquals(1, $item2->quantity);

        $this->assertCount(2, $purchase->getItems());
        $this->assertCount(1, $purchase->getStorePurchases());

        $this->assertEquals($product1->getStore(), $purchase->getStorePurchases()->getFirst()->getStore());
        $this->assertTrue($purchase->getStorePurchases()->getFirst()->getItems()->has($item1));
        $this->assertTrue($purchase->getStorePurchases()->getFirst()->getItems()->has($item2));
    }

    /**
     * @covers ::addPurchaseItem
     */
    public function testAddPurchaseItem()
    {
        $purchase = $this->getMock('CL\Purchases\Purchase', ['getStorePurchaseForStore']);

        $store = new Store();
        $storePurchase = new StorePurchase();
        $storePurchase->setStore($store);
        $purchase->getStorePurchases()->add($storePurchase);

        $purchase
            ->expects($this->once())
            ->method('getStorePurchaseForStore')
            ->with($this->identicalTo($store))
            ->will($this->returnValue($storePurchase));

        $purchaseItem = new PurchaseItem();
        $purchase->addPurchaseItem($store, $purchaseItem);

        $this->assertTrue($purchase->getItems()->has($purchaseItem));
        $this->assertTrue($storePurchase->getItems()->has($purchaseItem));
    }


    /**
     * @covers ::getStorePurchaseForStore
     */
    public function testGetStorePurchaseForStore()
    {
        $purchase = Purchase::find(2);
        $store = Store::find(1);

        $storePurchase = $purchase->getStorePurchaseForStore($store);

        $this->assertInstanceOf('CL\Purchases\StorePurchase', $storePurchase);
        $this->assertSame($store, $storePurchase->getStore());
        $this->assertSame($purchase, $storePurchase->getPurchase());
        $this->assertTrue($purchase->getStorePurchases()->has($storePurchase));

        $storePurchase2 = $purchase->getStorePurchaseForStore($store);
        $this->assertSame($storePurchase2, $storePurchase);
    }

    /**
     * @coversNothing
     */
    public function testIntegration()
    {
        $purchase = Purchase::find(1);

        $item1 = PurchaseItem::find(1);
        $item2 = PurchaseItem::find(2);
        $item3 = PurchaseItem::find(3);
        $item4 = PurchaseItem::find(4);

        $storePurchase1 = StorePurchase::find(1);
        $storePurchase2 = StorePurchase::find(2);
        $billing = Address::find(1);

        $items = $purchase->getItems();

        $this->assertSame([$item1, $item2, $item3, $item4], $purchase->getItems()->toArray());
        $this->assertSame([$storePurchase1, $storePurchase2], $purchase->getStorePurchases()->toArray());
        $this->assertSame($billing, $purchase->getBilling());
    }

    /**
     * @covers ::getRequestParameters
     */
    public function testGetRequestParameters()
    {
        $purchase  = Purchase::find(1);

        $data = $purchase->getRequestParameters(array());

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
    public function testStorePurchase()
    {
        $gateway = Omnipay::getFactory()->create('Dummy');

        $purchase = $this->getMock('CL\Purchases\Purchase', ['execute']);

        $params = ['test', 'test2'];

        $response = 'result response';

        $purchase
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->identicalTo($gateway),
                $this->equalTo('purchase'),
                $this->equalTo($params)
            )
            ->will($this->returnValue($response));

        $result = $purchase->purchase($gateway, $params);

        $this->assertEquals($response, $result);
    }

    /**
     * @covers ::complete
     */
    public function testComplete()
    {
        $gateway = Omnipay::getFactory()->create('Dummy');

        $purchase = $this->getMock('CL\Purchases\Purchase', ['execute']);

        $params = ['test', 'test2'];

        $response = 'result response';

        $purchase
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->identicalTo($gateway),
                $this->equalTo('complete'),
                $this->equalTo($params)
            )
            ->will($this->returnValue($response));

        $result = $purchase->complete($gateway, $params);

        $this->assertEquals($response, $result);
    }
}

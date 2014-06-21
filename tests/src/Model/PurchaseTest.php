<?php

namespace CL\Purchases\Test\Model;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Model\Store;
use CL\Purchases\Model\Basket;
use CL\Purchases\Repo;
use CL\Purchases\Model\Purchase;


/**
 * @coversDefaultClass CL\Purchases\Model\Purchase
 */
class PurchaseTest extends AbstractTestCase
{
    /**
     * @covers ::getRepo
     */
    public function testGetRepo()
    {
        $item = new Purchase();

        $repo = $item->getRepo();
        $repo2 = $item->getRepo();

        $this->assertSame($repo, $repo2);
        $this->assertInstanceOf('CL\Purchases\Model\Purchase', $item);
    }

    /**
     * @covers ::getStore
     * @covers ::setStore
     */
    public function testStore()
    {
        $product = new Purchase();

        $store = $product->getStore();

        $this->assertInstanceOf('CL\Purchases\Model\Store', $store);
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

        $this->assertInstanceOf('CL\Purchases\Model\Basket', $store);
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

        $this->assertEquals(Repo\BasketItem::get(), $items->getRel()->getForeignRepo());
    }
}

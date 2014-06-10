<?php

namespace CL\Purchases\Test\Model;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Model\Store;
use CL\Purchases\Repo;


/**
 * @coversDefaultClass CL\Purchases\Model\Store
 */
class StoreTest extends AbstractTestCase
{
    /**
     * @covers ::getRepo
     */
    public function testGetRepo()
    {
        $item = new Store();

        $repo = $item->getRepo();
        $repo2 = $item->getRepo();

        $this->assertSame($repo, $repo2);
        $this->assertInstanceOf('CL\Purchases\Model\Store', $item);
    }

    /**
     * @covers ::getProducts
     */
    public function testProducts()
    {
        $store = new Store();

        $items = $store->getProducts();

        $this->assertInstanceOf('Harp\Core\Repo\LinkMany', $items);
        $this->assertEquals(Repo\Product::get(), $items->getRel()->getForeignRepo());
    }

    /**
     * @covers ::getPurchases
     */
    public function testPurchases()
    {
        $store = new Store();

        $items = $store->getPurchases();

        $this->assertInstanceOf('Harp\Core\Repo\LinkMany', $items);
        $this->assertEquals(Repo\Purchase::get(), $items->getRel()->getForeignRepo());
    }
}

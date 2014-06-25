<?php

namespace CL\Purchases\Test;

use CL\Purchases\Store;
use CL\Purchases\ProductRepo;
use CL\Purchases\PurchaseRepo;


/**
 * @coversDefaultClass CL\Purchases\Store
 */
class StoreTest extends AbstractTestCase
{
    /**
     * @covers ::getProducts
     */
    public function testProducts()
    {
        $store = new Store();

        $items = $store->getProducts();

        $this->assertInstanceOf('Harp\Core\Repo\LinkMany', $items);
        $this->assertEquals(ProductRepo::get(), $items->getRel()->getForeignRepo());
    }

    /**
     * @covers ::getPurchases
     */
    public function testPurchases()
    {
        $store = new Store();

        $items = $store->getPurchases();

        $this->assertEquals(PurchaseRepo::get(), $items->getRel()->getForeignRepo());
    }
}

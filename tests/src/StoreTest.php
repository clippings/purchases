<?php

namespace CL\Purchases\Test;

use CL\Purchases\Store;
use CL\Purchases\Product;
use CL\Purchases\Purchase;

/**
 * @coversDefaultClass CL\Purchases\Store
 */
class StoreTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $store = Store::getRepo();

        $products = $store->getRelOrError('products');
        $this->assertEquals('CL\Purchases\Product', $products->getRepo()->getModelClass());

        $purchases = $store->getRelOrError('purchases');
        $this->assertEquals('CL\Purchases\Purchase', $purchases->getRepo()->getModelClass());

        $model = new Store();

        $this->assertFalse($model->validate());

        $errors = $model->getErrors()->humanize();

        $expected = 'name must be present';

        $this->assertEquals($expected, $errors);
    }

    /**
     * @covers ::getProducts
     */
    public function testProducts()
    {
        $store = new Store();

        $items = $store->getProducts();

        $this->assertInstanceOf('Harp\Harp\Repo\LinkMany', $items);
        $this->assertEquals(Product::getRepo(), $items->getRel()->getRepo());
    }

    /**
     * @covers ::getPurchases
     */
    public function testPurchases()
    {
        $store = new Store();

        $items = $store->getPurchases();

        $this->assertEquals(Purchase::getRepo(), $items->getRel()->getRepo());
    }
}

<?php

namespace CL\Purchases\Test;

use CL\Purchases\Store;
use CL\Purchases\Product;
use CL\Purchases\StorePurchase;

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

        $storePurchases = $store->getRelOrError('storePurchases');
        $this->assertEquals('CL\Purchases\StorePurchase', $storePurchases->getRepo()->getModelClass());

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
     * @covers ::getStorePurchases
     */
    public function testStorePurchases()
    {
        $store = new Store();

        $items = $store->getStorePurchases();

        $this->assertEquals(StorePurchase::getRepo(), $items->getRel()->getRepo());
    }
}

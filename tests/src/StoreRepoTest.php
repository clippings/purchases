<?php

namespace CL\Purchases\Test;

use CL\Purchases\StoreRepo;

/**
 * @coversDefaultClass CL\Purchases\StoreRepo
 */
class StoreRepoTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $store = new StoreRepo();

        $products = $store->getRelOrError('products');
        $this->assertInstanceOf('CL\Purchases\ProductRepo', $products->getForeignRepo());

        $purchases = $store->getRelOrError('purchases');
        $this->assertInstanceOf('CL\Purchases\PurchaseRepo', $purchases->getForeignRepo());

        $model = $store->newModel();

        $this->assertInstanceOf('CL\Purchases\Store', $model);

        $this->assertFalse($model->validate());

        $errors = $model->getErrors()->humanize();

        $expected = 'name must be present';

        $this->assertEquals($expected, $errors);
    }
}

<?php

namespace CL\Purchases\Test;

use CL\Purchases\ProductRepo;

/**
 * @coversDefaultClass CL\Purchases\ProductRepo
 */
class ProductRepoTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $product = new ProductRepo();

        $store = $product->getRelOrError('store');
        $this->assertInstanceOf('CL\Purchases\StoreRepo', $store->getForeignRepo());

        $items = $product->getRelOrError('productItems');
        $this->assertInstanceOf('CL\Purchases\ProductItemRepo', $items->getForeignRepo());

        $model = $product->newModel();

        $this->assertInstanceOf('CL\Purchases\Product', $model);

        $this->assertFalse($model->validate());

        $errors = $model->getErrors()->humanize();

        $expected = 'name must be present';

        $this->assertEquals($expected, $errors);
    }
}

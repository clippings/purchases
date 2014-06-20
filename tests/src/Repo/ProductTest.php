<?php

namespace CL\Purchases\Test\Repo;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Repo\Product;

/**
 * @coversDefaultClass CL\Purchases\Repo\Product
 */
class ProductTest extends AbstractTestCase
{
    /**
     * @covers ::newInstance
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $product = Product::newInstance();

        $store = $product->getRelOrError('store');
        $this->assertInstanceOf('CL\Purchases\Repo\Store', $store->getForeignRepo());

        $items = $product->getRelOrError('productItems');
        $this->assertInstanceOf('CL\Purchases\Repo\ProductItem', $items->getForeignRepo());

        $model = $product->newModel();

        $this->assertInstanceOf('CL\Purchases\Model\Product', $model);

        $this->assertFalse($model->validate());

        $errors = $model->getErrors()->humanize();

        $expected = 'name must be present';

        $this->assertEquals($expected, $errors);
    }
}

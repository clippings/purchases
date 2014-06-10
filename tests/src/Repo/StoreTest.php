<?php

namespace CL\Purchases\Test\Repo;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Repo\Store;

/**
 * @coversDefaultClass CL\Purchases\Repo\Store
 */
class StoreTest extends AbstractTestCase
{
    /**
     * @covers ::newInstance
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $store = Store::newInstance();

        $products = $store->getRelOrError('products');
        $this->assertInstanceOf('CL\Purchases\Repo\Product', $products->getForeignRepo());

        $purchases = $store->getRelOrError('purchases');
        $this->assertInstanceOf('CL\Purchases\Repo\Purchase', $purchases->getForeignRepo());

        $model = $store->newModel();

        $this->assertInstanceOf('CL\Purchases\Model\Store', $model);

        $this->assertFalse($model->validate());

        $errors = $model->getErrors()->humanize();

        $expected = 'name must be present';

        $this->assertEquals($expected, $errors);
    }
}

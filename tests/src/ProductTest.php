<?php

namespace CL\Purchases\Test;

use CL\Purchases\Store;
use CL\Purchases\Product;
use SebastianBergmann\Money\Money;
use SebastianBergmann\Money\Currency;


/**
 * @coversDefaultClass CL\Purchases\Product
 */
class ProductTest extends AbstractTestCase
{
    /**
     * @covers ::getStore
     * @covers ::setStore
     */
    public function testProduct()
    {
        $product = new Product();

        $store = $product->getStore();

        $this->assertInstanceOf('CL\Purchases\Store', $store);
        $this->assertTrue($store->isVoid());

        $store = new Store();

        $product->setStore($store);

        $this->assertSame($store, $product->getStore());
    }

    /**
     * @covers ::getProductItems
     */
    public function testProductItems()
    {
        $product = new Product();

        $items = $product->getProductItems();

        $this->assertContainsOnlyInstancesOf('CL\Purchases\ProductItem', $items->toArray());
    }
}

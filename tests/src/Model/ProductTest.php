<?php

namespace CL\Purchases\Test\Model;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Model\Store;
use CL\Purchases\Model\Product;
use SebastianBergmann\Money\Money;
use SebastianBergmann\Money\Currency;


/**
 * @coversDefaultClass CL\Purchases\Model\Product
 */
class ProductTest extends AbstractTestCase
{
    /**
     * @covers ::getRepo
     */
    public function testGetRepo()
    {
        $item = new Product();

        $repo = $item->getRepo();
        $repo2 = $item->getRepo();

        $this->assertSame($repo, $repo2);
        $this->assertInstanceOf('CL\Purchases\Model\Product', $item);
    }

    /**
     * @covers ::getStore
     * @covers ::setStore
     */
    public function testProduct()
    {
        $product = new Product();

        $store = $product->getStore();

        $this->assertInstanceOf('CL\Purchases\Model\Store', $store);
        $this->assertTrue($store->isVoid());

        $store = new Store();

        $product->setStore($store);

        $this->assertSame($store, $product->getStore());
    }

    /**
     * @covers ::getBasketItems
     */
    public function testItems()
    {
        $product = new Product();

        $items = $product->getBasketItems();

        $this->assertInstanceOf('Harp\Core\Repo\LinkMany', $items);
    }

    /**
     * @covers ::getPrice
     * @covers ::setPrice
     */
    public function testPrice()
    {
        $item = new Product(['price' => 100, 'currency' => 'EUR']);

        $this->assertEquals(new Money(100, new Currency('EUR')), $item->getPrice());

        $item->setPrice(new Money(2000, new Currency('EUR')));

        $this->assertEquals(new Money(2000, new Currency('EUR')), $item->getPrice());
    }
}

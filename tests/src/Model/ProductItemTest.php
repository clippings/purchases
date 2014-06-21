<?php

namespace CL\Purchases\Test\Model;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Model\Basket;
use CL\Purchases\Model\Product;
use CL\Purchases\Model\ProductItem;
use SebastianBergmann\Money\Money;
use SebastianBergmann\Money\Currency;


/**
 * @coversDefaultClass CL\Purchases\Model\ProductItem
 */
class ProductItemTest extends AbstractTestCase
{
    /**
     * @covers ::getRepo
     */
    public function testGetRepo()
    {
        $item = new ProductItem();

        $repo = $item->getRepo();
        $repo2 = $item->getRepo();

        $this->assertSame($repo, $repo2);
        $this->assertInstanceOf('CL\Purchases\Model\ProductItem', $item);
    }

    /**
     * @covers ::getProduct
     * @covers ::setProduct
     */
    public function testProduct()
    {
        $item = new ProductItem();

        $product = $item->getProduct();

        $this->assertInstanceOf('CL\Purchases\Model\Product', $product);
        $this->assertTrue($product->isVoid());

        $product = new Product();

        $item->setProduct($product);

        $this->assertSame($product, $item->getProduct());
    }

    /**
     * @covers ::getSourceValue
     */
    public function testGetSourceValue()
    {
        $item = new ProductItem();
        $item->setBasket(new Basket());
        $item->setProduct(new Product(['currency' => 'EUR', 'value' => 2000]));

        $this->assertEquals(new Money(2000, new Currency('EUR')), $item->getSourceValue());
    }

    /**
     * @covers ::getDescription
     */
    public function testGetDescription()
    {
        $item = new ProductItem();

        $item->setProduct(new Product(['name' => 'test name']));

        $this->assertEquals('test name', $item->getDescription());
    }
}

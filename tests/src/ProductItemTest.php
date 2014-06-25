<?php

namespace CL\Purchases\Test;

use CL\Purchases\Basket;
use CL\Purchases\Product;
use CL\Purchases\ProductItem;
use SebastianBergmann\Money\Money;
use SebastianBergmann\Money\Currency;


/**
 * @coversDefaultClass CL\Purchases\ProductItem
 */
class ProductItemTest extends AbstractTestCase
{
    /**
     * @covers ::getProduct
     * @covers ::setProduct
     */
    public function testProduct()
    {
        $item = new ProductItem();

        $product = $item->getProduct();

        $this->assertInstanceOf('CL\Purchases\Product', $product);
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

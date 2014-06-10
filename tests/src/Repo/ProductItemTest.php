<?php

namespace CL\Purchases\Test\Repo;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Repo\ProductItem;

/**
 * @coversDefaultClass CL\Purchases\Repo\ProductItem
 */
class ProductItemTest extends AbstractTestCase
{
    /**
     * @covers ::newInstance
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $productItem = ProductItem::newInstance();

        $basket = $productItem->getRelOrError('basket');
        $this->assertInstanceOf('CL\Purchases\Repo\Basket', $basket->getForeignRepo());

        $items = $productItem->getRelOrError('purchase');
        $this->assertInstanceOf('CL\Purchases\Repo\Purchase', $items->getForeignRepo());

        $purchases = $productItem->getRelOrError('refundItem');
        $this->assertInstanceOf('CL\Purchases\Repo\RefundItem', $purchases->getForeignRepo());

        $product = $productItem->getRelOrError('product');
        $this->assertInstanceOf('CL\Purchases\Repo\Product', $product->getForeignRepo());

        $model = $productItem->newModel();

        $this->assertInstanceOf('CL\Purchases\Model\ProductItem', $model);
    }
}

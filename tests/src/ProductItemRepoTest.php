<?php

namespace CL\Purchases\Test;

use CL\Purchases\ProductItemRepo;

/**
 * @coversDefaultClass CL\Purchases\ProductItemRepo
 */
class ProductItemRepoTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $productItem = new ProductItemRepo();

        $basket = $productItem->getRelOrError('basket');
        $this->assertInstanceOf('CL\Purchases\BasketRepo', $basket->getForeignRepo());

        $items = $productItem->getRelOrError('purchase');
        $this->assertInstanceOf('CL\Purchases\PurchaseRepo', $items->getForeignRepo());

        $purchases = $productItem->getRelOrError('refundItem');
        $this->assertInstanceOf('CL\Purchases\RefundItemRepo', $purchases->getForeignRepo());

        $product = $productItem->getRelOrError('product');
        $this->assertInstanceOf('CL\Purchases\ProductRepo', $product->getForeignRepo());

        $model = $productItem->newModel();

        $this->assertInstanceOf('CL\Purchases\ProductItem', $model);
    }
}

<?php

namespace CL\Purchases\Test\Repo;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Repo\Basket;

/**
 * @coversDefaultClass CL\Purchases\Repo\Basket
 */
class BasketTest extends AbstractTestCase
{
    /**
     * @covers ::newInstance
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $basket = Basket::newInstance();

        $billing = $basket->getRelOrError('billing');
        $this->assertInstanceOf('CL\Purchases\Repo\Address', $billing->getForeignRepo());

        $items = $basket->getRelOrError('items');
        $this->assertInstanceOf('CL\Purchases\Repo\BasketItem', $items->getForeignRepo());

        $purchases = $basket->getRelOrError('purchases');
        $this->assertInstanceOf('CL\Purchases\Repo\Purchase', $purchases->getForeignRepo());

        $model = $basket->newModel();

        $this->assertInstanceOf('CL\Purchases\Model\Basket', $model);
    }
}

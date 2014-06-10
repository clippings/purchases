<?php

namespace CL\Purchases\Test\Model;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Model\Store;
use CL\Purchases\Model\Basket;
use CL\Purchases\Model\Refund;
use CL\Purchases\Model\RefundItem;
use CL\Purchases\Repo;
use SebastianBergmann\Money\Money;
use SebastianBergmann\Money\Currency;

/**
 * @coversDefaultClass CL\Purchases\Model\Refund
 */
class RefundTest extends AbstractTestCase
{
    /**
     * @covers ::getRepo
     */
    public function testGetRepo()
    {
        $refund = new Refund();

        $repo = $refund->getRepo();
        $repo2 = $refund->getRepo();

        $this->assertSame($repo, $repo2);
        $this->assertInstanceOf('CL\Purchases\Model\Refund', $refund);
    }

    /**
     * @covers ::getBasket
     * @covers ::setBasket
     */
    public function testBasket()
    {
        $refund = new Refund();

        $store = $refund->getBasket();

        $this->assertInstanceOf('CL\Purchases\Model\Basket', $store);
        $this->assertTrue($store->isVoid());

        $store = new Basket();

        $refund->setBasket($store);

        $this->assertSame($store, $refund->getBasket());
    }

    /**
     * @covers ::getItems
     */
    public function testItems()
    {
        $refund = new Refund();

        $items = $refund->getItems();

        $this->assertInstanceOf('Harp\Core\Repo\LinkMany', $items);
        $this->assertEquals(Repo\RefundItem::get(), $items->getRel()->getForeignRepo());
    }

    /**
     * @covers ::getCurrency
     */
    public function testCurrency()
    {
        $refund = new Refund();
        $refund->setBasket(new Basket(['currency' => 'GBP']));

        $this->assertEquals(new Currency('GBP'), $refund->getCurrency());

        $refund->setBasket(new Basket(['currency' => 'EUR']));

        $this->assertEquals(new Currency('EUR'), $refund->getCurrency());
    }

    /**
     * @covers ::getTotalPrice
     */
    public function testGetTotalPrice()
    {
        $refund = new Refund();
        $refund->setBasket(new Basket(['currency' => 'GBP']));
        $refund->getItems()
            ->add(new RefundItem(['price' => 100]))
            ->add(new RefundItem(['price' => 250]));

        $this->assertEquals(new Money(350, new Currency('GBP')), $refund->getTotalPrice());
    }
}

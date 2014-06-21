<?php

namespace CL\Purchases\Test\Model;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Model\Purchase;
use CL\Purchases\Model\Refund;
use CL\Purchases\Repo;
use SebastianBergmann\Money\Money;
use SebastianBergmann\Money\Currency;
use Omnipay\Omnipay;

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
     * @covers ::getPurchase
     * @covers ::setPurchase
     */
    public function testPurchase()
    {
        $refund = new Refund();

        $purchase = $refund->getPurchase();

        $this->assertInstanceOf('CL\Purchases\Model\Purchase', $purchase);
        $this->assertTrue($purchase->isVoid());

        $purchase = new Purchase();

        $refund->setPurchase($purchase);

        $this->assertSame($purchase, $refund->getPurchase());
    }

    /**
     * @covers ::getItems
     */
    public function testItems()
    {
        $refund = new Refund();

        $items = $refund->getItems();

        $this->assertEquals(Repo\RefundItem::get(), $items->getRel()->getForeignRepo());
    }

    /**
     * @covers ::getRequestParameters
     */
    public function testGetRequestParameters()
    {
        $basket  = Repo\Refund::get()->find(1);

        $data = $basket->getRequestParameters(array());

        $expected = [
            'items' => [
                [
                    'name' => 4,
                    'description' => 'Refund for Product 4',
                    'price' => 40.0,
                    'quantity' => 1,
                ],
            ],
            'amount' => 40.0,
            'currency' => 'GBP',
            'transactionReference' => 1,
            'requestData' => [
                'amount' => '380.00',
                'reference' => '53a43cc327040',
                'success' => true,
                'message' => 'Success',
            ],
        ];

        $this->assertSame($expected, $data);
    }

    /**
     * @covers ::getCurrency
     */
    public function testCurrency()
    {
        $refund = new Refund();

        $currency = new Currency('EUR');

        $purchase = $this->getMock('CL\Purchases\Model\Purchase', ['getCurrency']);
        $purchase
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue($currency));

        $refund->setPurchase($purchase);

        $this->assertSame($currency, $refund->getCurrency());
    }

    /**
     * @covers ::refund
     */
    public function testRefund()
    {
        $gateway = Omnipay::getFactory()->create('Dummy');

        $refund = $this->getMock('CL\Purchases\Model\Refund', ['execute']);

        $params = ['test', 'test2'];

        $response = 'result response';

        $refund
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->identicalTo($gateway),
                $this->equalTo('refund'),
                $this->equalTo($params)
            )
            ->will($this->returnValue($response));

        $result = $refund->refund($gateway, $params);

        $this->assertEquals($response, $result);
    }
}

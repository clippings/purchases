<?php

namespace CL\Purchases\Test;

use CL\Purchases\Purchase;
use CL\Purchases\Refund;
use CL\Purchases\RefundItem;
use SebastianBergmann\Money\Money;
use SebastianBergmann\Money\Currency;
use Omnipay\Omnipay;

/**
 * @coversDefaultClass CL\Purchases\Refund
 */
class RefundTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $refund = Refund::getRepo();

        $purchase = $refund->getRelOrError('purchase');
        $this->assertEquals('CL\Purchases\Purchase', $purchase->getRepo()->getModelClass());
    }

    /**
     * @covers ::getPurchase
     * @covers ::setPurchase
     */
    public function testPurchase()
    {
        $refund = new Refund();

        $purchase = $refund->getPurchase();

        $this->assertInstanceOf('CL\Purchases\Purchase', $purchase);
        $this->assertTrue($purchase->isVoid());

        $purchase = new Purchase();

        $refund->setPurchase($purchase);

        $this->assertSame($purchase, $refund->getPurchase());
    }

    /**
     * @covers ::getRequestParameters
     */
    public function testGetRequestParameters()
    {
        $order  = Refund::find(1);

        $data = $order->getRequestParameters(array());

        $expected = [
            'amount' => 40.0,
            'currency' => 'GBP',
            'transactionReference' => 1,
            'requestData' => [
                'amount' => '380.00',
                'reference' => '53a43cc327040',
                'success' => true,
                'message' => 'Success',
            ],
            'items' => [
                [
                    'name' => 1,
                    'description' => 'Refund for 32NDWZEH',
                    'price' => 40.0,
                    'quantity' => 1,
                ],
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

        $purchase = $this->getMock('CL\Purchases\Purchase', ['getCurrency']);
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

        $refund = $this->getMock('CL\Purchases\Refund', ['execute']);

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

<?php

namespace CL\Purchases\Test;

use CL\Purchases\Order;
use CL\Purchases\Address;
use CL\Purchases\Product;

use Omnipay\Omnipay;

/**
 * @coversNothing
 */
class IntegrationTest extends AbstractTestCase
{
    public function testPurchase()
    {
        $order = new Order();

        $address  = Address::find(1);

        $product1 = Product::find(5);
        $product2 = Product::find(6);
        $product3 = Product::find(7);

        $order
            ->setBilling($address);

        $order
            ->addProduct($product1, 4)
            ->addProduct($product2)
            ->addProduct($product3)
            ->addProduct($product1);

        Order::save($order);

        $gateway = Omnipay::getFactory()->create('Dummy');

        $parameters = [
            'card' => [
                'number' => '4242424242424242',
                'expiryMonth' => 7,
                'expiryYear' => 2014,
                'cvv' => 123,
            ],
            'clientIp' => '192.168.0.1',
        ];

        $response = $order->purchase($gateway, $parameters);

        $this->assertTrue($response->isSuccessful());

        $this->assertEquals('Success', $response->getMessage());
    }
}

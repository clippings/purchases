<?php

namespace CL\Purchases\Test;

use CL\Purchases\Repo;
use CL\Purchases\Model;
use Omnipay\Omnipay;

/**
 * @coversNothing
 */
class PurchaseTest extends AbstractTestCase
{
    public function testPurchase()
    {
        $basket = new Model\Basket();

        $address  = Repo\Address::get()->find(1);

        $product1 = Repo\Product::get()->find(5);
        $product2 = Repo\Product::get()->find(6);
        $product3 = Repo\Product::get()->find(7);

        $basket
            ->setBilling($address);

        $basket
            ->addProduct($product1, 4)
            ->addProduct($product2)
            ->addProduct($product3)
            ->addProduct($product1);

        Repo\Basket::get()
            ->save($basket);

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

        $response = $basket->purchase($gateway, $parameters);

        $this->assertTrue($response->isSuccessful());

        $this->assertEquals('Success', $response->getMessage());
    }
}

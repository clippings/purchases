Purchases
=========

[![Build Status](https://travis-ci.org/clippings/purchases.png?branch=master)](https://travis-ci.org/clippings/purchases)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/clippings/purchases/badges/quality-score.png)](https://scrutinizer-ci.com/g/clippings/purchases/)
[![Code Coverage](https://scrutinizer-ci.com/g/clippings/purchases/badges/coverage.png)](https://scrutinizer-ci.com/g/clippings/purchases/)
[![Latest Stable Version](https://poser.pugx.org/clippings/purchases/v/stable.png)](https://packagist.org/packages/clippings/purchases)

This is a Kohana module that gives you out of the box functionality for multi-store purchases (each purchase may have Items from different sellers, each handling their portion of products independently)

It utilizes omnipay package

Usage
-----

The provided models work out of the box. You'll also have "Product" and "Store" models that you should use and extend.

```php
// Initialize some products and stores.
$store1 = new Store(['name' => 'My First Store']);
$product1 = new Product(['name' => 'My First Product', 'currency' => 'GBP', 'value' => 2000]);
$product2 = new Product(['name' => 'My Second Product', 'currency' => 'EUR', 'value' => 1000]);

$store1->getProducts()
    ->add($product1)
    ->add($product2);

$store2 = new Store(['name' => 'My Second Store']);
$product3 = new Product(['name' => 'My Third Product', 'currency' => 'GBP', 'value' => 5000]);

$store2->getProducts()
    ->add($product3);

Store::saveArray([$store1, $store2]);

// Now we can purchase something
$order = new Order();
$order
    ->addProduct($product1)
    ->addProduct($product2)
    ->addProduct($product3, 2);

// Freeze all the values of the order,
// so that it remains "frozen", even if prices of products change
$order->freeze();

// Initialize omnipay getway - here you will have Paypal, Stripe etc.
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

echo $response->isSuccessful();
```

Order
-----

Order is comprised of "OriderItems", an inherited model wich holds all the items that the user purchases. You'll usually extend OrderItems with items for your specific needs. for exampe "ProductItem".

Each OrderItem however, also belongs to a "Purchase" model. This links the order item to the specific store, for which it is intended.

## License

Copyright (c) 2014, Clippings Ltd. Developed by Ivan Kerin

Under BSD-3-Clause license, read LICENSE file.

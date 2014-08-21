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
$purchase = new Purchase();
$purchase
    ->addProduct($product1)
    ->addProduct($product2)
    ->addProduct($product3, 2);

// Freeze all the values of the order,
// so that it remains "frozen", even if prices of products change
$purchase->freeze();

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

$response = $purchase->purchase($gateway, $parameters);

echo $response->isSuccessful();
```

A diagram of the models
-----------------------

  ┌─────────┐   ┌──────────┐    ┌───────┐
  │ Address ├──→│ Purchase │    │ Store │
  └─────────┘   └─┬─────┬──┘    └─┬─────┘
                  │     ↓         ↓
                  │ ┌───────────────┐
                  │ │ StorePurchase │
                  │ └───────┬───────┘
                  │         ↓
                  │     ┌──────────────┐
                  └────→│ PurchaseItem │
  ┌─────────┐           ├──────────────┤
  │ Product ├──────────→│ ProductItem  │
  └─────────┘           └──────────────┘

Purchase is comprised of "PurchaseItems", a model which holds all the items that the user purchases.ProductItem exteds PurchaseItem so that they share the same table, using IheritedTrait.

Each PurchaseItem however, also belongs to a "StorePurchase" model, that represents the purchase for a specific store. This relation is managed automatically when you add products via the "addProduct" method or addPurchaseItem

Adding items to a purchase
-----------------------------

```php
$purchase = new Purchase();

// Adding a product
$purchase->addProduct($product);

// Adding multiple products
$purchase->addProduct($product, 5);

```

As the product already belongs to a specific store, this will create a specific StorePurchase for that store, if none exists. otherwise it will add the item to the existing StorePurchase. If this product already is present in the form of ProductItem, then it will increment the quantity.

```php
$purchase = new Purchase();

// Adding a purchaseItem
$purchase->addPurchaseItem($store, $purchaseItem);
```

If you need to add some other type of purchase item, that does not have an intrinsic relationship with a store, you can use the addPurchaseItem method. It will add any type of PurchaseItem.


Freezing assets
---------------

Since purchases use [harp-orm/transfers](https://github.com/clippings/transfers) package, it has the ability to "freeze" the values of purchase items when a purchase is actually completed.

```php
$purchase = new Purchase();
$product = new Product(['price' => 100]);

$purchase->addProduct($product, 3);

$productItem = $purchase->getItems()->getFirst();

// This will return the value of the product * quantity
// In this case 300
echo $productItem->getValue();

// Perform a freeze:
$purchase->freeze();

$product->price = 4000;

// Will still be 300
echo $productItem->getValue();
```

Freezing works by setting an "isFrozen" flag explicitly, so that later calls on ``freeze()`` will not recalculate values. You can take advantage of this by explicitly setting this flag to false for specific items and calling ``freeze()`` againg, to "refreeze" those values.

Currency Calculatens
--------------------

Purchases have a "currency" value that all the purchase items use. Any products with different currency will be converted using (harp-orm/money)[https://github.com/harp-orm/money]. That's why purchase items will not function properly, until they are attached to a purchase, which holds the currency for them.

```php
$purchase = new Purchase(['currency' => 'GBP']);
$product = new Product(['price' => 100, 'currency' => 'EUR']);

$purchase->addProduct($product, 3);

$productItem = $purchase->getItems()->getFirst();

// This will not 300, but convert it to GBP (around 240)
echo $productItem->getValue();
```

Refunding
---------

You can refund individual store purchases in full or with a partial amount.

```
$purchase = Purchase::find(1);
$storePurchase = $purchase->getStorePurchases()->getFirst();

$refund = new Refund();

// This will
$refund->setValue($storePurchase->getValue());

$storePurchase->getRefunds()->add($refund);

// Initialize omnipay getway - here you will have Paypal, Stripe etc.
$gateway = Omnipay::getFactory()->create('Dummy');

$refund->refund($gateway);
```

The refund object has a validation for its value, so that you do not refund more than the remaining amount for the store purchase, taking into account previous refunds.

## License

Copyright (c) 2014, Clippings Ltd. Developed by Ivan Kerin

Under BSD-3-Clause license, read LICENSE file.

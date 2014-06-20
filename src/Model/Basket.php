<?php

namespace CL\Purchases\Model;

use CL\Purchases\Repo;
use CL\Transfer\Model\AbstractTransfer;
use Harp\Money\Model\CurrencyTrait;
use Harp\Timestamps\TimestampsModelTrait;
use Omnipay\Common\GatewayInterface;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Basket extends AbstractTransfer
{
    use TimestampsModelTrait;
    use CurrencyTrait;

    public $billingId;

    public function getRepo()
    {
        return Repo\Basket::get();
    }

    /**
     * @return LinkMany\Purchases
     */
    public function getPurchases()
    {
        return $this->getLink('purchases');
    }

    public function getItems()
    {
        return $this->getLink('items');
    }

    public function getProductItems()
    {
        return $this->getLink('items')->get()->filter(function (BasketItem $item) {
            return $item instanceof ProductItem;
        });
    }

    public function performFreeze()
    {
        parent::performFreeze();

        foreach ($this->getPurchases() as $purchase) {
            $purchase->freeze();
        }
    }

    public function performUnfreeze()
    {
        parent::performUnfreeze();

        foreach ($this->getPurchases() as $purchase) {
            $purchase->unfreeze();
        }
    }

    public function getBilling()
    {
        return $this->getLink('billing')->get();
    }

    public function setBilling(Address $billing)
    {
        return $this->getLink('billing')->set($billing);
    }

    public function getRequestParameters(array $defaultParameters)
    {
        $parameters = parent::getRequestParameters($defaultParameters);

        $billing = $this->getBilling();

        $parameters['card'] = [
            'firstName' => $billing->firstName,
            'lastName'  => $billing->lastName,
            'address1'  => $billing->line1,
            'address2'  => $billing->line2,
            'city'      => $billing->getCity()->name,
            'country'   => $billing->getCountry()->code,
            'postcode'  => $billing->postCode,
            'phone'     => $billing->phone,
            'email'     => $billing->email,
        ];

        return array_merge_recursive($parameters, $defaultParameters);
    }

    public function getPurchaseForStore(Store $store)
    {
        foreach ($this->getPurchases() as $purchase) {
            if ($purchase->getStore() === $store) {
                return $purchase;
            }
        }

        $purchase = new Purchase();
        $purchase->setStore($store);
        $purchase->setBasket($this);
        $this->getPurchases()->add($purchase);

        return $purchase;
    }

    public function addProduct(Product $product, $quantity = 1)
    {
        foreach ($this->getProductItems() as $item) {
            if ($item->getProduct() === $product) {
                $item->quantity += $quantity;
                return $this;
            }
        }

        $purchase = $this->getPurchaseForStore($product->getStore());

        $item = new ProductItem(['quantity' => $quantity]);
        $item->setBasket($this);
        $item->setProduct($product);
        $item->setPurchase($purchase);

        $purchase->getItems()->add($item);
        $this->getItems()->add($item);

        return $this;
    }

    public function purchase(GatewayInterface $gateway, array $parameters)
    {
        return $this->execute($gateway, 'purchase', $parameters);
    }

    public function complete(GatewayInterface $gateway, array $parameters)
    {
        return $this->execute($gateway, 'complete', $parameters);
    }
}

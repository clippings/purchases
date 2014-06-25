<?php

namespace CL\Purchases;

use CL\Transfer\AbstractTransfer;
use Harp\Money\CurrencyTrait;
use Harp\Timestamps\TimestampsTrait;
use Harp\RandomKey\RandomKeyTrait;
use Omnipay\Common\GatewayInterface;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Basket extends AbstractTransfer
{
    const REPO = 'CL\Purchases\BasketRepo';

    use TimestampsTrait;
    use RandomKeyTrait;
    use CurrencyTrait;

    public $billingId;

    /**
     * @return \Harp\Core\Repo\LinkMany
     */
    public function getPurchases()
    {
        return $this->getLink('purchases');
    }

    /**
     * @return \Harp\Core\Repo\LinkMany
     */
    public function getItems()
    {
        return $this->getLink('items');
    }

    /**
     * @return \Harp\Core\Model\Models
     */
    public function getProductItems()
    {
        return $this->getLink('items')->get()->filter(function (BasketItem $item) {
            return $item instanceof ProductItem;
        });
    }

    /**
     * Freeze all items and purchases
     */
    public function performFreeze()
    {
        parent::performFreeze();

        foreach ($this->getPurchases() as $purchase) {
            $purchase->freeze();
        }
    }

    /**
     * Unfreeze all items and purchases
     */
    public function performUnfreeze()
    {
        parent::performUnfreeze();

        foreach ($this->getPurchases() as $purchase) {
            $purchase->unfreeze();
        }
    }

    /**
     * @return Address
     */
    public function getBilling()
    {
        return $this->getLink('billing')->get();
    }

    /**
     * @param Address $billing
     */
    public function setBilling(Address $billing)
    {
        return $this->getLink('billing')->set($billing);
    }

    /**
     * @param  array  $defaultParameters
     * @return array
     */
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

    /**
     * Find a purchase for the given Store or create a new one
     * @param  Store  $store
     * @return Purchase
     */
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

    /**
     * Add a ProductItem for the given product / quantity.
     * If product item exists, increase the quantity
     *
     * @param Product $product
     * @param integer $quantity
     */
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

    /**
     * @param  GatewayInterface $gateway
     * @param  array            $parameters
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function purchase(GatewayInterface $gateway, array $parameters)
    {
        return $this->execute($gateway, 'purchase', $parameters);
    }

    /**
     * @param  GatewayInterface $gateway
     * @param  array            $parameters
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function complete(GatewayInterface $gateway, array $parameters)
    {
        return $this->execute($gateway, 'complete', $parameters);
    }
}
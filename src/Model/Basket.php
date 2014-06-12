<?php

namespace CL\Purchases\Model;

use Harp\Harp\AbstractModel;
use Harp\Core\Model\Models;
use CL\Purchases\Repo;
use CL\Purchases\LinkMany;
use SebastianBergmann\Money\Currency;
use SebastianBergmann\Money\Money;
use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Item;
use Closure;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Basket extends AbstractModel
{
    const PAYMENT_PENDING = 1;
    const PAID = 2;

    public $id;
    public $currency = 'GBP';
    public $status;
    public $billingId;
    public $deletedAt;

    public function loadData()
    {
        if (is_string($this->omnipay)) {
            $this->omnipay = unserialize($this->omnipay);
        }
    }

    public function getCurrency()
    {
        return new Currency($this->currency);
    }

    public function getTotal()
    {
        $prices = $this->getItems()->get()->map(function(BasketItem $item){
            return $item->getPrice()->getAmount();
        });

        return new Money(array_sum($prices), $this->getCurrency());
    }

    public function getProductTotal()
    {
        $prices = $this->getItems()->onlyProduct()->map(function(ProductItem $item){
            return $item->getPrice()->getAmount();
        });

        return new Money(array_sum($prices), $this->getCurrency());
    }

    public function getRefundTotal()
    {
        $prices = $this->getItems()->onlyRefund()->map(function(RefundItem $item){
            return $item->getPrice()->getAmount();
        });

        return new Money(array_sum($prices), $this->getCurrency());
    }

    public function freeze()
    {
        foreach ($this->getItems() as $item) {
            $item->freeze();
        }

        return $this;
    }

    public function unfreeze()
    {
        foreach ($this->getItems() as $item) {
            $item->unfreeze();
        }

        return $this;
    }

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

    /**
     * @return LinkMany\Items
     */
    public function getItems()
    {
        return $this->getLink('items');
    }

    public function getBilling()
    {
        return $this->getLink('billing')->get();
    }

    public function setBilling(Address $billing)
    {
        return $this->getLink('billing')->set($billing);
    }

    public function isPaymentPending()
    {
        return $this->status === self::PAYMENT_PENDING;
    }

    public function isPaid()
    {
        return $this->status === self::PAID;
    }

    public function getRequestParameters()
    {
        $parameters = [];

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

        $items = [];

        foreach ($this->getItems() as $item) {
            $parameters['items'] []= [
                'name' => $item->getId(),
                'description' => $item->getName(),
                'price' => (float) ($item->getPrice()->getAmount() / 100),
                'quantity' => $item->quantity,
            ];
        }

        $parameters['amount'] = (float) ($this->getTotal()->getAmount() / 100);
        $parameters['currency'] = $this->currency;
        $parameters['transactionReference'] = $this->getId();

        return $parameters;
    }

    public function addProduct(Product $product, $quantity = 1)
    {
        $item = $this->getItems()->addForProduct($product, $quantity);

        $purchase = $this->getPurchases()->addForStore($product->getStore());

        $purchase->getBasketItems()->add($item);

        return $this;
    }
}

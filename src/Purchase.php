<?php

namespace CL\Purchases;

use Harp\Harp\Rel;
use Harp\Harp\Config;
use Harp\Harp\AbstractModel;
use Harp\Timestamps\TimestampsTrait;
use Harp\Money\CurrencyTrait;
use Harp\RandomKey\RandomKeyTrait;
use CL\Transfer\TransferTrait;
use CL\Transfer\ItemGroupTrait;
use Omnipay\Common\GatewayInterface;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Purchase extends AbstractModel
{
    use TimestampsTrait;
    use RandomKeyTrait;
    use CurrencyTrait;
    use ItemGroupTrait;
    use TransferTrait;

    public static function initialize(Config $config)
    {
        TransferTrait::initialize($config);
        ItemGroupTrait::initialize($config);
        TimestampsTrait::initialize($config);
        RandomKeyTrait::initialize($config);
        CurrencyTrait::initialize($config);

        $config
            ->setTable('Purchase')
            ->addRels([
                new Rel\HasMany('items', $config, PurchaseItem::getRepo(), [
                    'foreignKey' => 'transferId',
                    'inverseOf' => 'purchase',
                ]),
                new Rel\HasMany('storePurchases', $config, StorePurchase::getRepo(), [
                    'inverseOf' => 'purchase',
                ]),
                new Rel\BelongsTo('billing', $config, Address::getRepo()),
            ]);
    }

    public $billingId;

    /**
     * @return \Harp\Harp\Repo\LinkMany
     */
    public function getStorePurchases()
    {
        return $this->all('storePurchases');
    }

    /**
     * @return \Harp\Harp\Repo\LinkMany
     */
    public function getItems()
    {
        return $this->all('items');
    }

    /**
     * @return \Harp\Harp\Model\Models
     */
    public function getProductItems()
    {
        return $this->getItems()->filter(function (PurchaseItem $item) {
            return $item instanceof ProductItem;
        });
    }

    public function freezeStorePurchases()
    {
        foreach ($this->getStorePurchases() as $purchase) {
            $purchase->freeze();
        }

        return $this;
    }

    public function unfreezeStorePurchases()
    {
        foreach ($this->getStorePurchases() as $purchase) {
            $purchase->unfreeze();
        }

        return $this;
    }

    /**
     * Freeze all items and purchases
     */
    public function performFreeze()
    {
        $this
            ->freezeItems()
            ->freezeValue()
            ->freezeStorePurchases();
    }

    /**
     * Unfreeze all items and purchases
     */
    public function performUnfreeze()
    {
        $this
            ->unfreezeItems()
            ->unfreezeValue()
            ->unfreezeStorePurchases();
    }

    /**
     * @return Address
     */
    public function getBilling()
    {
        return $this->get('billing');
    }

    /**
     * @param Address $billing
     */
    public function setBilling(Address $billing)
    {
        return $this->set('billing', $billing);
    }

    /**
     * @param  array  $defaultParameters
     * @return array
     */
    public function getRequestParameters(array $defaultParameters)
    {
        $parameters = $this->getTransferParameters();

        $parameters['items'] = [];

        foreach ($this->getStorePurchases() as $storePurchase) {
            $parameters['items'] []= [
                'name' => $storePurchase->getId(),
                'description' => "Items from {$storePurchase->getStore()->name}",
                'price' => (float) ($storePurchase->getValue()->getAmount() / 100),
                'quantity' => 1,
            ];
        }

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
    public function getStorePurchaseForStore(Store $store)
    {
        foreach ($this->getStorePurchases() as $storePurchase) {
            if ($storePurchase->getStore() === $store) {
                return $storePurchase;
            }
        }

        $storePurchase = new StorePurchase();
        $storePurchase->setStore($store);
        $this->getStorePurchases()->add($storePurchase);

        return $storePurchase;
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

        $storePurchase = $this->getStorePurchaseForStore($product->getStore());

        $item = new ProductItem(['quantity' => $quantity]);
        $item->setProduct($product);

        $storePurchase->getItems()->add($item);
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
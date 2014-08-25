<?php

namespace CL\Purchases;

use Harp\Harp\Config;
use Harp\Harp\Rel;
use Harp\Timestamps\TimestampsTrait;
use Harp\RandomKey\RandomKeyTrait;
use Harp\Money\MoneyObjects;
use Harp\Harp\AbstractModel;
use CL\Transfer\ItemGroupTrait;
use SebastianBergmann\Money\Money;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class StorePurchase extends AbstractModel
{
    use TimestampsTrait;
    use RandomKeyTrait;
    use ItemGroupTrait;

    public static function initialize(Config $config)
    {
        ItemGroupTrait::initialize($config);
        TimestampsTrait::initialize($config);
        RandomKeyTrait::initialize($config);

        $config
            ->setTable('StorePurchase')
            ->addRels([
                new Rel\BelongsTo('purchase', $config, Purchase::getRepo()),
                new Rel\BelongsTo('store', $config, Store::getRepo()),
                new Rel\HasMany('items', $config, PurchaseItem::getRepo(), ['inverseOf' => 'storePurchase']),
                new Rel\HasMany('refunds', $config, Refund::getRepo(), ['inverseOf' => 'storePurchase']),
            ]);
    }

    /**
     * @var integer
     */
    public $id;

    /**
     * @var integer
     */
    public $purchaseId;

    /**
     * @var integer
     */
    public $storeId;

    /**
     * @return \SebastianBergmann\Money\Currency
     */
    public function getCurrency()
    {
        return $this->getPurchase()->getCurrency();
    }

    /**
     * Add a purchase item by updateing this and the parent purchase as well
     *
     * @param PurchaseItem $item
     */
    public function addPurchaseItem(PurchaseItem $item)
    {
        $this->getItems()->add($item);
        $this->getPurchase()->getItems()->add($item);

        return $this;
    }

    /**
     * @return \Harp\Harp\Repo\LinkMany
     */
    public function getItems()
    {
        return $this->all('items');
    }

    /**
     * @return \Harp\Harp\Repo\LinkMany
     */
    public function getRefunds()
    {
        return $this->all('refunds');
    }

    /**
     * Sum the values from all the refunds,
     * if there are no refunds return Money(0)
     *
     * @return Money
     */
    public function getRefundsValue()
    {
        return MoneyObjects::sum($this->getRefunds()->invoke('getValue'));
    }

    /**
     * Return the value for this store purchase that is not refunded yet
     * (Value - RefundsValue)
     *
     * @return Money
     */
    public function getRemainingValue()
    {
        return $this->getValue()->subtract($this->getRefundsValue());
    }

    /**
     * Check if all the money for this purchase has been refunded
     * If no refunds have been made, will return false
     *
     * @return boolean
     */
    public function isFullyRefunded()
    {
        return (count($this->getRefunds()) > 0 and $this->getRemainingValue()->getAmount() === 0);
    }

    /**
     * @return Purchase
     */
    public function getPurchase()
    {
        return $this->get('purchase');
    }

    /**
     * @param Purchase $purchase
     */
    public function setPurchase(Purchase $purchase)
    {
        $this->set('purchase', $purchase);

        return $this;
    }

    /**
     * @return Store
     */
    public function getStore()
    {
        return $this->get('store');
    }

    /**
     * @param Store $store
     */
    public function setStore(Store $store)
    {
        $this->set('store', $store);

        return $this;
    }
}

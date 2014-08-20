<?php

namespace CL\Purchases;

use Harp\Harp\Config;
use Harp\Harp\Rel;
use Harp\Timestamps\TimestampsTrait;
use Harp\RandomKey\RandomKeyTrait;
use Harp\Harp\AbstractModel;
use CL\Transfer\ItemGroupTrait;

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

    public $id;
    public $purchaseId;
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

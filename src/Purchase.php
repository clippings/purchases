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
class Purchase extends AbstractModel
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
            ->setTable('Purchase')
            ->addRels([
                new Rel\BelongsTo('order', $config, Order::getRepo()),
                new Rel\BelongsTo('store', $config, Store::getRepo()),
                new Rel\HasMany('items', $config, OrderItem::getRepo()),
                new Rel\HasMany('refunds', $config, Refund::getRepo()),
            ]);
    }

    public $orderId;
    public $storeId;

    /**
     * @return \SebastianBergmann\Money\Currency
     */
    public function getCurrency()
    {
        return $this->getOrder()->getCurrency();
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
     * @return Order
     */
    public function getOrder()
    {
        return $this->get('order');
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->set('order', $order);

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

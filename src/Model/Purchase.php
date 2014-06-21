<?php

namespace CL\Purchases\Model;

use Harp\Harp\AbstractModel;
use Harp\Timestamps\Model\TimestampsTrait;
use CL\Transfer\Model\AbstractItemGroup;
use CL\Purchases\Repo;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Purchase extends AbstractItemGroup
{
    use TimestampsTrait;

    public $basketId;
    public $storeId;

    /**
     * @return \SebastianBergmann\Money\Currency
     */
    public function getCurrency()
    {
        return $this->getBasket()->getCurrency();
    }

    /**
     * @return Repo\Purchase
     */
    public function getRepo()
    {
        return Repo\Purchase::get();
    }

    /**
     * @return \Harp\Core\Repo\LinkMany
     */
    public function getItems()
    {
        return $this->getLink('items');
    }

    /**
     * @return \Harp\Core\Repo\LinkMany
     */
    public function getRefunds()
    {
        return $this->getLink('refunds');
    }

    /**
     * @return Basket
     */
    public function getBasket()
    {
        return $this->getLink('basket')->get();
    }

    /**
     * @param Basket $basket
     */
    public function setBasket(Basket $basket)
    {
        return $this->getLink('basket')->set($basket);
    }

    /**
     * @return Store
     */
    public function getStore()
    {
        return $this->getLink('store')->get();
    }

    /**
     * @param Store $store
     */
    public function setStore(Store $store)
    {
        return $this->getLink('store')->set($store);
    }
}

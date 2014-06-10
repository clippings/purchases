<?php

namespace CL\Purchases\Model;

use Harp\Harp\AbstractModel;
use Harp\Timestamps\TimestampsModelTrait;
use CL\Purchases\Repo;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Purchase extends AbstractModel
{
    use TimestampsModelTrait;

    public function getRepo()
    {
        return Repo\Purchase::get();
    }

    public $id;
    public $status;
    public $basketId;
    public $storeId;
    public $deletedAt;

    public function getBasketItems()
    {
        return $this->getLink('basketItems');
    }

    public function getBasket()
    {
        return $this->getLink('basket')->get();
    }

    public function setBasket(Basket $basket)
    {
        return $this->getLink('basket')->set($basket);
    }

    public function getStore()
    {
        return $this->getLink('store')->get();
    }

    public function setStore(Store $store)
    {
        return $this->getLink('store')->set($store);
    }
}

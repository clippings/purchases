<?php

namespace CL\Purchases\Model;

use Harp\Harp\AbstractModel;
use Harp\Money\Model\CurrencyTrait;
use Harp\Money\Model\ValueTrait;
use Harp\Core\Model\SoftDeleteTrait;
use CL\Purchases\Repo;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Product extends AbstractModel
{
    use CurrencyTrait;
    use ValueTrait;
    use SoftDeleteTrait;

    public function getRepo()
    {
        return Repo\Product::get();
    }

    public $id;
    public $name;
    public $storeId;
    public $title;

    public function getProductItems()
    {
        return $this->getLink('productItems');
    }

    public function getStore()
    {
        return $this->getLink('store')->get();
    }

    public function setStore(Store $store)
    {
        $this->getLink('store')->set($store);

        return $this;
    }
}

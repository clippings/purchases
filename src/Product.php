<?php

namespace CL\Purchases;

use Harp\Harp\AbstractModel;
use Harp\Money\CurrencyTrait;
use Harp\Money\ValueTrait;
use Harp\Core\Model\SoftDeleteTrait;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Product extends AbstractModel
{
    const REPO = 'CL\Purchases\ProductRepo';

    use CurrencyTrait;
    use ValueTrait;
    use SoftDeleteTrait;

    public $id;
    public $name;
    public $storeId;
    public $title;

    /**
     * @return \Harp\Core\Repo\LinkMany
     */
    public function getProductItems()
    {
        return $this->getLink('productItems');
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
        $this->getLink('store')->set($store);

        return $this;
    }
}

<?php

namespace CL\Purchases;

use Harp\Harp\AbstractModel;
use Harp\Core\Model\SoftDeleteTrait;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Store extends AbstractModel
{
    const REPO = 'CL\Purchases\StoreRepo';

    use SoftDeleteTrait;

    public $id;
    public $name;

    /**
     * @return \Harp\Core\Repo\LinkMany
     */
    public function getProducts()
    {
        return $this->getLink('products');
    }

    /**
     * @return \Harp\Core\Repo\LinkMany
     */
    public function getPurchases()
    {
        return $this->getLink('purchases');
    }
}

<?php

namespace CL\Purchases\Model;

use Harp\Harp\AbstractModel;
use Harp\Core\Model\SoftDeleteTrait;
use CL\Purchases\Repo;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Store extends AbstractModel
{
    use SoftDeleteTrait;

    public $id;
    public $name;

    public function getRepo()
    {
        return Repo\Store::get();
    }

    public function getProducts()
    {
        return $this->getLink('products');
    }

    public function getPurchases()
    {
        return $this->getLink('purchases');
    }
}

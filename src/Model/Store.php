<?php

namespace CL\Purchases\Model;

use Harp\Harp\AbstractModel;
use CL\Purchases\Repo;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Store extends AbstractModel
{
    public function getRepo()
    {
        return Repo\Store::get();
    }

    public $id;
    public $name;
    public $deletedAt;

    public function getProducts()
    {
        return $this->getLink('products');
    }

    public function getPurchases()
    {
        return $this->getLink('purchases');
    }
}

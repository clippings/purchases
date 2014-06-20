<?php

namespace CL\Purchases\Repo;

use Harp\Harp\AbstractRepo;
use Harp\Harp\Rel;
use Harp\Validate\Assert;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Store extends AbstractRepo
{
    public static function newInstance()
    {
        return new Store('CL\Purchases\Model\Store');
    }

    public function initialize()
    {
        $this
            ->setSoftDelete(true)
            ->addRels([
                new Rel\HasMany('products', $this, Product::get()),
                new Rel\HasMany('purchases', $this, Purchase::get()),
            ])
            ->addAsserts([
                new Assert\Present('name'),
                new Assert\LengthLessThan('name', 150),
            ]);
    }
}

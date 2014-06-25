<?php

namespace CL\Purchases;

use Harp\Harp\AbstractRepo;
use Harp\Harp\Rel;
use Harp\Validate\Assert;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class StoreRepo extends AbstractRepo
{
    public function initialize()
    {
        $this
            ->setModelClass('CL\Purchases\Store')
            ->setSoftDelete(true)
            ->addRels([
                new Rel\HasMany('products', $this, ProductRepo::get()),
                new Rel\HasMany('purchases', $this, PurchaseRepo::get()),
            ])
            ->addAsserts([
                new Assert\Present('name'),
                new Assert\LengthLessThan('name', 150),
            ]);
    }
}

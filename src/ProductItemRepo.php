<?php

namespace CL\Purchases;

use Harp\Harp\Rel;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class ProductItemRepo extends BasketItemRepo
{
    public function initialize()
    {
        parent::initialize();

        $this
            ->setModelClass('CL\Purchases\ProductItem')
            ->setRootRepo(BasketItemRepo::get())
            ->addRels([
                new Rel\BelongsTo('product', $this, ProductRepo::get(), ['key' => 'refId']),
            ]);
    }
}

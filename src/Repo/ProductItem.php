<?php

namespace CL\Purchases\Repo;

use Harp\Harp\Rel;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class ProductItem extends BasketItem
{
    public static function newInstance()
    {
        return new ProductItem('CL\Purchases\Model\ProductItem');
    }

    public function initialize()
    {
        parent::initialize();

        $this
            ->setRootRepo(BasketItem::get())
            ->addRels([
                new Rel\BelongsTo('product', $this, Product::get(), ['key' => 'refId']),
            ]);
    }
}

<?php

namespace CL\Purchases\Repo;

use Harp\Harp\AbstractRepo;
use Harp\Harp\Rel;
use Harp\Timestamps\TimestampsRepoTrait;
use CL\Purchases\Repo;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Purchase extends AbstractRepo
{
    use TimestampsRepoTrait;

    public static function newInstance()
    {
        return new Purchase('CL\Purchases\Model\Purchase');
    }

    public function initialize()
    {
        $this
            ->initializeTimestamps()
            ->setSoftDelete(true)
            ->addRels([
                new Rel\BelongsTo('basket', $this, Basket::get()),
                new Rel\BelongsTo('store', $this, Store::get()),
                new Rel\HasMany('basketItems', $this, BasketItem::get()),
            ]);
    }
}

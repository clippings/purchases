<?php

namespace CL\Purchases\Repo;

use Harp\Harp\AbstractRepo;
use Harp\Harp\Rel;
use CL\Purchases\Repo;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Refund extends AbstractRepo
{
    public static function newInstance()
    {
        return new Refund('CL\Purchases\Model\Refund');
    }

    public function initialize()
    {
        $this
            ->setSoftDelete(true)
            ->addRels([
                new Rel\BelongsTo('basket', $this, Basket::get()),
                new Rel\HasMany('items', $this, RefundItem::get()),
            ]);
    }
}

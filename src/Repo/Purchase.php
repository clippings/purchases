<?php

namespace CL\Purchases\Repo;

use CL\Transfer\Repo\AbstractTransfer;
use Harp\Timestamps\Repo\TimestampsTrait;
use Harp\RandomKey\Repo\RandomKeyTrait;
use Harp\Harp\Rel;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Purchase extends AbstractTransfer
{
    use RandomKeyTrait;
    use TimestampsTrait;

    public static function newInstance()
    {
        return new Purchase('CL\Purchases\Model\Purchase');
    }

    public function initialize()
    {
        parent::initialize();

        $this
            ->initializeTimestamps()
            ->initializeRandomKey()
            ->addRels([
                new Rel\BelongsTo('basket', $this, Basket::get()),
                new Rel\BelongsTo('store', $this, Store::get()),
                new Rel\HasMany('items', $this, BasketItem::get()),
                new Rel\HasMany('refunds', $this, Refund::get()),
            ]);
    }
}

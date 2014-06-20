<?php

namespace CL\Purchases\Repo;

use Harp\Transfer\Repo\AbstractTransfer;
use Harp\Harp\Rel;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Refund extends AbstractTransfer
{
    public static function newInstance()
    {
        return new Refund('CL\Purchases\Model\Refund');
    }

    public function initialize()
    {
        $this
            ->addRels([
                new Rel\BelongsTo('purchase', $this, Purchase::get()),
                new Rel\HasMany('items', $this, RefundItem::get(), ['foreignKey' => 'transferId']),
            ]);
    }
}

<?php

namespace CL\Purchases;

use CL\Transfer\AbstractTransferRepo;
use Harp\Harp\Rel;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class RefundRepo extends AbstractTransferRepo
{
    public function initialize()
    {
        $this
            ->setModelClass('CL\Purchases\Refund')
            ->addRels([
                new Rel\BelongsTo('purchase', $this, PurchaseRepo::get()),
                new Rel\HasMany('items', $this, RefundItemRepo::get(), ['foreignKey' => 'transferId']),
            ]);
    }
}

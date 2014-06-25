<?php

namespace CL\Purchases;

use CL\Transfer\AbstractItemRepo;
use Harp\Harp\Rel;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class BasketItemRepo extends AbstractItemRepo
{
    public function initialize()
    {
        parent::initialize();

        $this
            ->setModelClass('CL\Purchases\BasketItem')
            ->setInherited(true)
            ->setTable('BasketItem')
            ->addRels([
                new Rel\BelongsTo('basket', $this, BasketRepo::get(), ['key' => 'transferId']),
                new Rel\BelongsTo('purchase', $this, PurchaseRepo::get()),
                new Rel\HasOne('refundItem', $this, RefundItemRepo::get(), ['foreignKey' => 'refId']),
            ]);
    }
}

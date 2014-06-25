<?php

namespace CL\Purchases;

use CL\Transfer\AbstractTransferRepo;
use Harp\Timestamps\TimestampsRepoTrait;
use Harp\RandomKey\RandomKeyRepoTrait;
use Harp\Harp\Rel;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class PurchaseRepo extends AbstractTransferRepo
{
    use RandomKeyRepoTrait;
    use TimestampsRepoTrait;

    public function initialize()
    {
        parent::initialize();

        $this
            ->setModelClass('CL\Purchases\Purchase')
            ->initializeTimestamps()
            ->initializeRandomKey()
            ->addRels([
                new Rel\BelongsTo('basket', $this, BasketRepo::get()),
                new Rel\BelongsTo('store', $this, StoreRepo::get()),
                new Rel\HasMany('items', $this, BasketItemRepo::get()),
                new Rel\HasMany('refunds', $this, RefundRepo::get()),
            ]);
    }
}

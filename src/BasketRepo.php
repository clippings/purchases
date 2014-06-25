<?php

namespace CL\Purchases;

use CL\Transfer\AbstractTransferRepo;
use Harp\Money\CurrencyRepoTrait;
use Harp\Harp\Rel;
use Harp\Validate\Assert;
use Harp\Timestamps\TimestampsRepoTrait;
use Harp\RandomKey\RandomKeyRepoTrait;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class BasketRepo extends AbstractTransferRepo
{
    use RandomKeyRepoTrait;
    use TimestampsRepoTrait;
    use CurrencyRepoTrait;

    public function initialize()
    {
        parent::initialize();

        $this
            ->setModelClass('CL\Purchases\Basket')
            ->initializeTimestamps()
            ->initializeRandomKey()
            ->addRels([
                new Rel\HasMany('items', $this, BasketItemRepo::get(), ['foreignKey' => 'transferId']),
                new Rel\HasMany('purchases', $this, PurchaseRepo::get()),
                new Rel\BelongsTo('billing', $this, AddressRepo::get()),
            ]);
    }
}

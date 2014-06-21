<?php

namespace CL\Purchases\Repo;

use CL\Transfer\Repo\AbstractTransfer;
use Harp\Money\Repo\CurrencyTrait;
use Harp\Harp\Rel;
use Harp\Validate\Assert;
use Harp\Timestamps\Repo\TimestampsTrait;
use Harp\RandomKey\Repo\RandomKeyTrait;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Basket extends AbstractTransfer
{
    use RandomKeyTrait;
    use TimestampsTrait;
    use CurrencyTrait;

    public static function newInstance()
    {
        return new Basket('CL\Purchases\Model\Basket');
    }

    public function initialize()
    {
        parent::initialize();

        $this
            ->initializeTimestamps()
            ->initializeRandomKey()
            ->addRels([
                new Rel\HasMany('items', $this, BasketItem::get(), ['foreignKey' => 'transferId']),
                new Rel\HasMany('purchases', $this, Purchase::get()),
                new Rel\BelongsTo('billing', $this, Address::get()),
            ]);
    }
}

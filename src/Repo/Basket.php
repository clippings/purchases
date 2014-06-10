<?php

namespace CL\Purchases\Repo;

use Harp\Harp\AbstractRepo;
use Harp\Harp\Rel;
use Harp\Validate\Assert;
use CL\Purchases\Repo;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Basket extends AbstractRepo
{
    public static function newInstance()
    {
        return new Basket('CL\Purchases\Model\Basket');
    }

    public function initialize()
    {
        $this
            ->setSoftDelete(true)
            ->addRels([
                (new Rel\HasMany('items', $this, BasketItem::get()))
                    ->setLinkClass('CL\Purchases\Collection\BasketItems'),
                (new Rel\HasMany('purchases', $this, Purchase::get()))
                    ->setLinkClass('CL\Purchases\Collection\Purchases'),
                new Rel\HasMany('refunds', $this, Refund::get()),
                new Rel\BelongsTo('billing', $this, Address::get()),
            ])
            ->addAsserts([
                new Assert\Present('currency'),
                new Assert\LengthEquals('currency', 3),
            ]);
    }
}

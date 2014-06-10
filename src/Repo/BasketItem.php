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
class BasketItem extends AbstractRepo
{
    public static function newInstance()
    {
        return new BasketItem('CL\Purchases\Model\BasketItem');
    }

    public function initialize()
    {
        $this
            ->setInherited(true)
            ->setSoftDelete(true)
            ->addRels([
                new Rel\BelongsTo('basket', $this, Basket::get()),
                new Rel\BelongsTo('purchase', $this, Purchase::get()),
                new Rel\HasOne('refundItem', $this, RefundItem::get(), ['foreignKey' => 'refId']),
            ]);
    }
}

<?php

namespace CL\Purchases\Repo;

use CL\Transfer\Repo\AbstractItem;
use CL\Purchases\Repo;
use Harp\Harp\Rel;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class RefundItem extends AbstractItem
{
    public static function newInstance()
    {
        return new RefundItem('CL\Purchases\Model\RefundItem');
    }

    public function initialize()
    {
        parent::initialize();

        $this
            ->setTable('RefundItem')
            ->addRels([
                new Rel\BelongsTo('refund', $this, Refund::get()),
                new Rel\BelongsTo('item', $this, BasketItem::get(), ['key' => 'refId']),
            ]);
    }
}

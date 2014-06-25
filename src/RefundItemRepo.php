<?php

namespace CL\Purchases;

use CL\Transfer\AbstractItemRepo;
use Harp\Harp\Rel;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class RefundItemRepo extends AbstractItemRepo
{
    public function initialize()
    {
        parent::initialize();

        $this
            ->setModelClass('CL\Purchases\RefundItem')
            ->setTable('RefundItem')
            ->addRels([
                new Rel\BelongsTo('refund', $this, RefundRepo::get()),
                new Rel\BelongsTo('item', $this, BasketItemRepo::get(), ['key' => 'refId']),
            ]);
    }
}

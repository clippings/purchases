<?php

namespace CL\Purchases\Repo;

use Harp\Harp\AbstractRepo;
use Harp\Harp\Rel;
use Harp\Validate\Assert;
use CL\Purchases\Repo;
use Harp\Money\Repo\CurrencyTrait;
use Harp\Money\Repo\ValueTrait;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Product extends AbstractRepo
{
    use CurrencyTrait;
    use ValueTrait;

    public static function newInstance()
    {
        return new Product('CL\Purchases\Model\Product');
    }

    public function initialize()
    {
        $this
            ->initializeCurrency()
            ->initializeValue()
            ->setSoftDelete(true)
            ->addRels([
                new Rel\BelongsTo('store', $this, Store::get()),
                new Rel\HasMany('productItems', $this, ProductItem::get(), ['foreignKey' => 'refId']),
            ])
            ->addAsserts([
                new Assert\Present('name'),
                new Assert\LengthLessThan('name', 150),
            ]);
    }
}

<?php

namespace CL\Purchases;

use Harp\Harp\AbstractRepo;
use Harp\Harp\Rel;
use Harp\Validate\Assert;
use Harp\Money\CurrencyRepoTrait;
use Harp\Money\ValueRepoTrait;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class ProductRepo extends AbstractRepo
{
    use CurrencyRepoTrait;
    use ValueRepoTrait;

    public function initialize()
    {
        $this
            ->setModelClass('CL\Purchases\Product')
            ->initializeCurrency()
            ->initializeValue()
            ->setSoftDelete(true)
            ->addRels([
                new Rel\BelongsTo('store', $this, StoreRepo::get()),
                new Rel\HasMany('productItems', $this, ProductItemRepo::get(), ['foreignKey' => 'refId']),
            ])
            ->addAsserts([
                new Assert\Present('name'),
                new Assert\LengthLessThan('name', 150),
            ]);
    }
}

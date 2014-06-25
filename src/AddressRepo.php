<?php

namespace CL\Purchases;

use Harp\Harp\AbstractRepo;
use Harp\Harp\Rel;
use Harp\Validate\Assert;
use Harp\Locations\CountryRepo;
use Harp\Locations\CityRepo;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class AddressRepo extends AbstractRepo
{
    public function initialize()
    {
        $this
            ->setModelClass('CL\Purchases\Address')
            ->setSoftDelete(true)
            ->addRels([
                new Rel\BelongsTo('city', $this, CityRepo::get()),
                new Rel\BelongsTo('country', $this, CountryRepo::get()),
                new Rel\HasOne('basket', $this, BasketRepo::get(), ['foreignKey' => 'billingId']),
            ])
            ->addAsserts([
                new Assert\Present('firstName'),
                new Assert\Present('lastName'),
                new Assert\Present('email'),
                new Assert\Present('phone'),
                new Assert\Present('postCode'),
                new Assert\Present('line1'),
                new Assert\Email('email'),
            ]);
    }
}

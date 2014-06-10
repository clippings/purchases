<?php

namespace CL\Purchases\Repo;

use Harp\Harp\AbstractRepo;
use Harp\Harp\Rel;
use Harp\Validate\Assert;
use Harp\Locations\Repo\Country;
use Harp\Locations\Repo\City;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Address extends AbstractRepo
{
    public static function newInstance()
    {
        return new Address('CL\Purchases\Model\Address');
    }

    public function initialize()
    {
        $this
            ->setSoftDelete(true)
            ->addRels([
                new Rel\BelongsTo('city', $this, City::get()),
                new Rel\BelongsTo('country', $this, Country::get()),
                new Rel\HasOne('basket', $this, Basket::get(), ['foreignKey' => 'billingId']),
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

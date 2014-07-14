<?php

namespace CL\Purchases;

use Harp\Harp\AbstractModel;
use Harp\Harp\Model\SoftDeleteTrait;
use Harp\Locations\City;
use Harp\Locations\Country;
use Harp\Harp\Rel;
use Harp\Harp\Config;
use Harp\Validate\Assert;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Address extends AbstractModel
{
    public static function initialize(Config $config)
    {
        SoftDeleteTrait::initialize($config);

        $config
            ->addRels([
                new Rel\BelongsTo('city', $config, City::getRepo()),
                new Rel\BelongsTo('country', $config, Country::getRepo()),
                new Rel\HasOne('order', $config, Order::getRepo(), ['foreignKey' => 'billingId']),
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

    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $postCode;
    public $line1;
    public $line2;
    public $cityId;
    public $countryId;

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->get('order');
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->set('order', $order);

        return $this;
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->get('city');
    }

    /**
     * @param City $city
     */
    public function setCity(City $city)
    {
        $this->set('city', $city);

        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->get('country');
    }

    /**
     * @param Country $country
     */
    public function setCountry(Country $country)
    {
        $this->set('country', $country);

        return $this;
    }
}

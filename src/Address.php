<?php

namespace CL\Purchases;

use Harp\Harp\AbstractModel;
use Harp\Locations\City;
use Harp\Locations\Country;
use SebastianBergmann\Money\Currency;
use SebastianBergmann\Money\Money;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Address extends AbstractModel
{
    const REPO = 'CL\Purchases\AddressRepo';

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
     * @return Basket
     */
    public function getBasket()
    {
        return $this->getLink('basket')->get();
    }

    /**
     * @param Basket $basket
     */
    public function setBasket(Basket $basket)
    {
        return $this->getLink('basket')->set($basket);
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->getLink('city')->get();
    }

    /**
     * @param City $city
     */
    public function setCity(City $city)
    {
        return $this->getLink('city')->set($city);
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->getLink('country')->get();
    }

    /**
     * @param Country $country
     */
    public function setCountry(Country $country)
    {
        return $this->getLink('country')->set($country);
    }
}

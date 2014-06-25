<?php

namespace CL\Purchases\Test;

use CL\Purchases\Address;
use CL\Purchases\Basket;
use Harp\Locations;


/**
 * @coversDefaultClass CL\Purchases\Address
 */
class AddressTest extends AbstractTestCase
{
    /**
     * @covers ::getCity
     * @covers ::setCity
     */
    public function testCity()
    {
        $address = new Address();

        $city = $address->getCity();

        $this->assertInstanceOf('Harp\Locations\City', $city);
        $this->assertTrue($city->isVoid());

        $city = new Locations\City();

        $address->setCity($city);

        $this->assertSame($city, $address->getCity());
    }

    /**
     * @covers ::getCountry
     * @covers ::setCountry
     */
    public function testCountry()
    {
        $address = new Address();

        $country = $address->getCountry();

        $this->assertInstanceOf('Harp\Locations\Country', $country);
        $this->assertTrue($country->isVoid());

        $country = new Locations\Country();

        $address->setCountry($country);

        $this->assertSame($country, $address->getCountry());
    }

    /**
     * @covers ::getBasket
     * @covers ::setBasket
     */
    public function testBasket()
    {
        $address = new Address();

        $basket = $address->getBasket();

        $this->assertInstanceOf('CL\Purchases\Basket', $basket);
        $this->assertTrue($basket->isVoid());

        $basket = new Basket();

        $address->setBasket($basket);

        $this->assertSame($basket, $address->getBasket());
    }

    /**
     * @coversNothing
     */
    public function testIntegration()
    {
        $address = Address::find(1);
        $basket = Basket::find(1);
        $country = Locations\Country::find(1);
        $city = Locations\City::find(2);

        $this->assertSame($basket, $address->getBasket());
        $this->assertSame($city, $address->getCity());
        $this->assertSame($country, $address->getCountry());
    }
}

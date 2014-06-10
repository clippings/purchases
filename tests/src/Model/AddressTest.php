<?php

namespace CL\Purchases\Test\Model;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Model\Address;
use CL\Purchases\Repo;
use CL\Purchases\Model\Basket;
use Harp\Locations;


/**
 * @coversDefaultClass CL\Purchases\Model\Address
 */
class AddressTest extends AbstractTestCase
{
    /**
     * @covers ::getRepo
     */
    public function testGetRepo()
    {
        $address = new Address();

        $repo = $address->getRepo();
        $repo2 = $address->getRepo();

        $this->assertSame($repo, $repo2);
        $this->assertInstanceOf('CL\Purchases\Model\Address', $address);
    }

    /**
     * @covers ::getCity
     * @covers ::setCity
     */
    public function testCity()
    {
        $address = new Address();

        $city = $address->getCity();

        $this->assertInstanceOf('Harp\Locations\Model\City', $city);
        $this->assertTrue($city->isVoid());

        $city = new Locations\Model\City();

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

        $this->assertInstanceOf('Harp\Locations\Model\Country', $country);
        $this->assertTrue($country->isVoid());

        $country = new Locations\Model\Country();

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

        $this->assertInstanceOf('CL\Purchases\Model\Basket', $basket);
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
        $address = Repo\Address::get()->find(1);
        $basket = Repo\Basket::get()->find(1);
        $country = Locations\Repo\Country::get()->find(1);
        $city = Locations\Repo\City::get()->find(2);

        $this->assertSame($basket, $address->getBasket());
        $this->assertSame($city, $address->getCity());
        $this->assertSame($country, $address->getCountry());
    }
}

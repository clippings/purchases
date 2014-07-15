<?php

namespace CL\Purchases\Test;

use CL\Purchases\Address;
use CL\Purchases\Purchase;
use Harp\Locations\City;
use Harp\Locations\Country;


/**
 * @coversDefaultClass CL\Purchases\Address
 */
class AddressTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $address = Address::getRepo();

        $purchase = $address->getRelOrError('purchase');
        $this->assertEquals('CL\Purchases\Purchase', $purchase->getRepo()->getModelClass());

        $country = $address->getRelOrError('country');
        $this->assertEquals('Harp\Locations\Country', $country->getRepo()->getModelClass());

        $city = $address->getRelOrError('city');
        $this->assertEquals('Harp\Locations\City', $city->getRepo()->getModelClass());

        $model = new Address();

        $this->assertFalse($model->validate());

        $errors = $model->getErrors()->humanize();

        $expected = 'firstName must be present, lastName must be present, email must be present, phone must be present, postCode must be present, line1 must be present';

        $this->assertEquals($expected, $errors);
    }

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

        $city = new City();

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

        $country = new Country();

        $address->setCountry($country);

        $this->assertSame($country, $address->getCountry());
    }

    /**
     * @covers ::getPurchase
     * @covers ::setPurchase
     */
    public function testPurchase()
    {
        $address = new Address();

        $purchase = $address->getPurchase();

        $this->assertInstanceOf('CL\Purchases\Purchase', $purchase);
        $this->assertTrue($purchase->isVoid());

        $purchase = new Purchase();

        $address->setPurchase($purchase);

        $this->assertSame($purchase, $address->getPurchase());
    }

    /**
     * @coversNothing
     */
    public function testIntegration()
    {
        $address = Address::find(1);
        $purchase = Purchase::find(1);
        $country = Country::find(1);
        $city = City::find(2);

        $this->assertSame($purchase, $address->getPurchase());
        $this->assertSame($city, $address->getCity());
        $this->assertSame($country, $address->getCountry());
    }
}

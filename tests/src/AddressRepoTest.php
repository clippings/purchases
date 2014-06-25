<?php

namespace CL\Purchases\Test;

use CL\Purchases\AddressRepo;

/**
 * @coversDefaultClass CL\Purchases\AddressRepo
 */
class AddressRepoTest extends AbstractTestCase
{
    /**
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $address = new AddressRepo();

        $basket = $address->getRelOrError('basket');
        $this->assertInstanceOf('CL\Purchases\BasketRepo', $basket->getForeignRepo());

        $country = $address->getRelOrError('country');
        $this->assertInstanceOf('Harp\Locations\CountryRepo', $country->getForeignRepo());

        $city = $address->getRelOrError('city');
        $this->assertInstanceOf('Harp\Locations\CityRepo', $city->getForeignRepo());

        $model = $address->newModel();

        $this->assertInstanceOf('CL\Purchases\Address', $model);

        $this->assertFalse($model->validate());

        $errors = $model->getErrors()->humanize();

        $expected = 'firstName must be present, lastName must be present, email must be present, phone must be present, postCode must be present, line1 must be present';

        $this->assertEquals($expected, $errors);


    }
}

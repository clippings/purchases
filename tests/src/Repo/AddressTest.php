<?php

namespace CL\Purchases\Test\Repo;

use CL\Purchases\Test\AbstractTestCase;
use CL\Purchases\Repo\Address;

/**
 * @coversDefaultClass CL\Purchases\Repo\Address
 */
class AddressTest extends AbstractTestCase
{
    /**
     * @covers ::newInstance
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $address = Address::newInstance();

        $basket = $address->getRelOrError('basket');
        $this->assertInstanceOf('CL\Purchases\Repo\Basket', $basket->getForeignRepo());

        $country = $address->getRelOrError('country');
        $this->assertInstanceOf('Harp\Locations\Repo\Country', $country->getForeignRepo());

        $city = $address->getRelOrError('city');
        $this->assertInstanceOf('Harp\Locations\Repo\City', $city->getForeignRepo());

        $model = $address->newModel();

        $this->assertInstanceOf('CL\Purchases\Model\Address', $model);

        $this->assertFalse($model->validate());

        $errors = $model->getErrors()->humanize();

        $expected = 'firstName must be present, lastName must be present, email must be present, phone must be present, postCode must be present, line1 must be present';

        $this->assertEquals($expected, $errors);


    }
}

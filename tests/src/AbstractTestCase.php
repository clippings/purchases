<?php

namespace CL\Purchases\Test;

use Harp\Query\DB;
use Harp\Harp\Repo\Container;
use CL\CurrencyConvert\Converter;
use CL\CurrencyConvert\NullSource;
use PHPUnit_Framework_TestCase;

abstract class AbstractTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var TestLogger
     */
    protected $logger;

    /**
     * @return TestLogger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    public function setUp()
    {
        parent::setUp();

        $this->logger = new TestLogger();

        DB::setConfig([
            'dsn' => 'mysql:dbname=clippings/purchases;host=127.0.0.1',
            'username' => 'root',
            'escaping' => DB::ESCAPING_MYSQL,
        ]);

        Converter::initialize(new NullSource());

        DB::get()->execute('ALTER TABLE OrderItem AUTO_INCREMENT = 6');
        DB::get()->execute('ALTER TABLE Purchase AUTO_INCREMENT = 3');

        DB::get()->setLogger($this->logger);
        DB::get()->beginTransaction();

        Container::clear();
    }

    public function tearDown()
    {
        DB::get()->rollback();

        parent::tearDown();
    }
}

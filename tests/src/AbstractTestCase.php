<?php

namespace CL\Purchases\Test;

use Harp\Query\DB;
use CL\Purchases\Repo;
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
        ]);

        Converter::initialize(new NullSource());

        DB::get()->execute('ALTER TABLE BasketItem AUTO_INCREMENT = 6');
        DB::get()->execute('ALTER TABLE Purchase AUTO_INCREMENT = 3');

        DB::get()->setLogger($this->logger);
        DB::get()->beginTransaction();

        Repo\Basket::get()->clear();
        Repo\BasketItem::get()->clear();
        Repo\ProductItem::get()->clear();
        Repo\RefundItem::get()->clear();
        Repo\Refund::get()->clear();
        Repo\Store::get()->clear();
        Repo\Product::get()->clear();
    }

    public function tearDown()
    {
        DB::get()->rollback();

        parent::tearDown();
    }
}

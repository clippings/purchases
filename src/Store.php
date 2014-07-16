<?php

namespace CL\Purchases;

use Harp\Harp\AbstractModel;
use Harp\Harp\Config;
use Harp\Harp\Model\SoftDeleteTrait;
use Harp\Harp\Rel;
use Harp\Validate\Assert;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Store extends AbstractModel
{
    use SoftDeleteTrait;

    public static function initialize(Config $config)
    {
        SoftDeleteTrait::initialize($config);

        $config
            ->addRels([
                new Rel\HasMany('products', $config, Product::getRepo(), ['inverseOf' => 'store']),
                new Rel\HasMany('storePurchases', $config, StorePurchase::getRepo(), ['inverseOf' => 'store']),
            ])
            ->addAsserts([
                new Assert\Present('name'),
                new Assert\LengthLessThan('name', 150),
            ]);
    }

    public $id;
    public $name;

    /**
     * @return \Harp\Harp\Repo\LinkMany
     */
    public function getProducts()
    {
        return $this->all('products');
    }

    /**
     * @return \Harp\Harp\Repo\LinkMany
     */
    public function getStorePurchases()
    {
        return $this->all('storePurchases');
    }
}

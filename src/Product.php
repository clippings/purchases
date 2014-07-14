<?php

namespace CL\Purchases;

use Harp\Harp\AbstractModel;
use Harp\Money\CurrencyTrait;
use Harp\Money\ValueTrait;
use Harp\Harp\Model\SoftDeleteTrait;
use Harp\Harp\Rel;
use Harp\Harp\Config;
use Harp\Validate\Assert;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Product extends AbstractModel
{
    use CurrencyTrait;
    use ValueTrait;
    use SoftDeleteTrait;

    public static function initialize(Config $config)
    {
        CurrencyTrait::initialize($config);
        ValueTrait::initialize($config);
        SoftDeleteTrait::initialize($config);

        $config
            ->addRels([
                new Rel\BelongsTo('store', $config, Store::getRepo()),
                new Rel\HasMany('productItems', $config, ProductItem::getRepo(), ['foreignKey' => 'refId']),
            ])
            ->addAsserts([
                new Assert\Present('name'),
                new Assert\LengthLessThan('name', 150),
            ]);
    }

    public $id;
    public $name;
    public $storeId;
    public $title;

    /**
     * @return \Harp\Harp\Repo\LinkMany
     */
    public function getProductItems()
    {
        return $this->all('productItems');
    }

    /**
     * @return Store
     */
    public function getStore()
    {
        return $this->get('store');
    }

    /**
     * @param Store $store
     */
    public function setStore(Store $store)
    {
        $this->set('store', $store);

        return $this;
    }
}

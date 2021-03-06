<?php

namespace CL\Purchases;

use Harp\Harp\AbstractModel;
use Harp\Harp\Model\InheritedTrait;
use CL\Transfer\ItemTrait;
use SebastianBergmann\Money\Money;
use Harp\Timestamps\TimestampsTrait;
use Harp\Harp\Config;
use Harp\Harp\Rel;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class PurchaseItem extends AbstractModel
{
    use InheritedTrait;
    use TimestampsTrait;
    use ItemTrait;

    public static function initialize(Config $config)
    {
        ItemTrait::initialize($config);
        InheritedTrait::initialize($config);
        TimestampsTrait::initialize($config);

        $config
            ->addRels([
                new Rel\BelongsTo('purchase', $config, Purchase::getRepo()),
                new Rel\BelongsTo('storePurchase', $config, StorePurchase::getRepo()),
            ]);
    }

    /**
     * @var integer
     */
    public $id;

    /**
     * @var integer
     */
    public $purchaseId;

    /**
     * @var integer
     */
    public $storePurchaseId;

    /**
     * @return \SebastianBergmann\Money\Currency
     */
    public function getCurrency()
    {
        return $this->getPurchase()->getCurrency();
    }

    /**
     * This method should be extended to provide the actual value of a referenced model
     *
     * @return Money
     */
    public function getSourceValue()
    {
        return new Money($this->value, $this->getCurrency());
    }

    /**
     * Value * Quantity
     *
     * @return Money
     */
    public function getTotalValue()
    {
        return $this->getValue()->multiply($this->quantity);
    }

    /**
     * @return StorePurchase
     */
    public function getStorePurchase()
    {
        return $this->get('storePurchase');
    }

    /**
     * @param  StorePurchase $storePurchase
     * @return static
     */
    public function setStorePurchase(StorePurchase $storePurchase)
    {
        $this->set('storePurchase', $storePurchase);

        return $this;
    }

    /**
     * @return Purchase
     */
    public function getPurchase()
    {
        return $this->get('purchase');
    }

    /**
     * @param Purchase $purchase
     * @return static
     */
    public function setPurchase(Purchase $purchase)
    {
        $this->set('purchase', $purchase);

        return $this;
    }
}

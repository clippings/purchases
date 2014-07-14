<?php

namespace CL\Purchases;

use Harp\Harp\AbstractModel;
use Harp\Harp\Model\InheritedTrait;
use CL\Transfer\ItemTrait;
use SebastianBergmann\Money\Money;
use Harp\Harp\Config;
use Harp\Harp\Rel;


/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class OrderItem extends AbstractModel
{
    use InheritedTrait;
    use ItemTrait;

    public static function initialize(Config $config)
    {
        ItemTrait::initialize($config);
        InheritedTrait::initialize($config);

        $config
            ->addRels([
                new Rel\BelongsTo('order', $config, Order::getRepo(), ['key' => 'transferId']),
                new Rel\BelongsTo('purchase', $config, Purchase::getRepo()),
            ]);
    }

    public $purchaseId;

    /**
     * @return \SebastianBergmann\Money\Currency
     */
    public function getCurrency()
    {
        return $this->getOrder()->getCurrency();
    }

    /**
     * @return Money
     */
    public function getSourceValue()
    {
        return new Money($this->value, $this->getCurrency());
    }

    /**
     * Value * Quantity
     * @return Money
     */
    public function getTotalValue()
    {
        return $this->getValue()->multiply($this->quantity);
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
     */
    public function setPurchase(Purchase $purchase)
    {
        $this->set('purchase', $purchase);

        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->get('order');
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->set('order', $order);

        return $this;
    }
}

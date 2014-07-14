<?php

namespace CL\Purchases;

use CL\Transfer\TransferTrait;
use Omnipay\Common\GatewayInterface;
use Harp\Harp\Rel;
use Harp\Harp\Config;
use Harp\Money\ValueTrait;
use Harp\Harp\AbstractModel;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Refund extends AbstractModel
{
    use TransferTrait;
    use ValueTrait;

    public static function initialize(Config $config)
    {
        ValueTrait::initialize($config);
        TransferTrait::initialize($config);

        $config
            ->addRels([
                new Rel\BelongsTo('purchase', $config, Purchase::getRepo())
            ]);
    }

    public $purchaseId;

    /**
     * @return \SebastianBergmann\Money\Currency
     */
    public function getCurrency()
    {
        return $this->getPurchase()->getCurrency();
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
        return $this->set('purchase', $purchase);
    }

    /**
     * @param  array $defaultParameters
     * @return array
     */
    public function getRequestParameters(array $defaultParameters)
    {
        $parameters = $this->getTransferParameters();
        $purchase = $this->getPurchase();

        $parameters['requestData'] = $purchase->getOrder()->responseData;
        $parameters['items'] []= [
            'name' => $purchase->getId(),
            'description' => "Refund for {$purchase->uniqueKey}",
            'price' => (float) ($this->getValue()->getAmount() / 100),
            'quantity' => 1,
        ];

        return array_merge_recursive($parameters, $defaultParameters);
    }

    /**
     * @param  GatewayInterface                          $refund
     * @param  array                                     $parameters
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function refund(GatewayInterface $refund, array $parameters)
    {
        return $this->execute($refund, 'refund', $parameters);
    }
}

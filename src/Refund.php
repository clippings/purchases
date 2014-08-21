<?php

namespace CL\Purchases;

use CL\Transfer\TransferTrait;
use Omnipay\Common\GatewayInterface;
use Harp\Timestamps\TimestampsTrait;
use Harp\Harp\Rel;
use Harp\Harp\Config;
use Harp\Money\ValueTrait;
use Harp\Harp\AbstractModel;
use Harp\Validate\Assert;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Refund extends AbstractModel
{
    use TransferTrait;
    use TimestampsTrait;
    use ValueTrait;

    public static function initialize(Config $config)
    {
        ValueTrait::initialize($config);
        TransferTrait::initialize($config);
        TimestampsTrait::initialize($config);

        $config
            ->addRels([
                new Rel\BelongsTo('storePurchase', $config, StorePurchase::getRepo())
            ])
            ->addAsserts([
                new Assert\GreaterThan('value', 0),
                new Assert\Callback('value', function ($refund) {
                    return $refund->getValue()->lessThanOrEqual(
                        $refund->getStorePurchase()->getRemainingValue()
                    );
                }, ':name is more than the remaining value'),
            ]);
    }

    /**
     * @var integer
     */
    public $storePurchaseId;

    /**
     * @return \SebastianBergmann\Money\Currency
     */
    public function getCurrency()
    {
        return $this->getStorePurchase()->getCurrency();
    }

    /**
     * @return StorePurchase
     */
    public function getStorePurchase()
    {
        return $this->get('storePurchase');
    }

    /**
     * @param StorePurchase $storePurchase
     */
    public function setStorePurchase(StorePurchase $storePurchase)
    {
        return $this->set('storePurchase', $storePurchase);
    }

    /**
     * @param  array $defaultParameters
     * @return array
     */
    public function getRequestParameters(array $defaultParameters)
    {
        $parameters = $this->getTransferParameters();
        $storePurchase = $this->getStorePurchase();

        $parameters['requestData'] = $storePurchase->getPurchase()->responseData;
        $parameters['items'] []= [
            'name' => $storePurchase->getId(),
            'description' => "Refund for {$storePurchase->uniqueKey}",
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
    public function refund(GatewayInterface $refund, array $parameters = array())
    {
        return $this->execute($refund, 'refund', $parameters);
    }
}

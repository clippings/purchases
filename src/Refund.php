<?php

namespace CL\Purchases;

use Harp\Harp\AbstractModel;
use CL\Transfer\AbstractTransfer;
use Omnipay\Common\GatewayInterface;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Refund extends AbstractTransfer
{
    const REPO = 'CL\Purchases\RefundRepo';

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
        return $this->getLink('purchase')->get();
    }

    /**
     * @param Purchase $purchase
     */
    public function setPurchase(Purchase $purchase)
    {
        return $this->getLink('purchase')->set($purchase);
    }

    /**
     * @return \Harp\Core\Repo\LinkMany
     */
    public function getItems()
    {
        return $this->getLink('items');
    }

    /**
     * @param  array  $defaultParameters
     * @return array
     */
    public function getRequestParameters(array $defaultParameters)
    {
        $defaultParameters['requestData'] = $this->getPurchase()->getBasket()->responseData;

        return parent::getRequestParameters($defaultParameters);
    }

    /**
     * @param  GatewayInterface $refund
     * @param  array            $parameters
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function refund(GatewayInterface $refund, array $parameters)
    {
        return $this->execute($refund, 'refund', $parameters);
    }
}

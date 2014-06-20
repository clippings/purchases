<?php

namespace CL\Purchases\Model;

use Harp\Harp\AbstractModel;
use CL\Purchases\Repo;
use CL\Transfer\Model\AbstractTransfer;
use Omnipay\Common\GatewayInterface;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Refund extends AbstractTransfer
{
    public $purchaseId;

    public function getRepo()
    {
        return Repo\Refund::get();
    }

    public function getCurrency()
    {
        return $this->getPurchase()->getCurrency();
    }

    public function getPurchase()
    {
        return $this->getLink('purchase')->get();
    }

    public function setPurchase(Purchase $purchase)
    {
        return $this->getLink('purchase')->set($purchase);
    }

    public function getItems()
    {
        return $this->getLink('items');
    }

    public function getRequestParameters(array $defaultParameters)
    {
        $defaultParameters['requestData'] = $this->getPurchase()->getBasket()->responseData;

        return parent::getRequestParameters($defaultParameters);
    }

    public function refund(GatewayInterface $refund, array $parameters)
    {
        $this->execute($refund, 'refund', $parameters);
    }
}

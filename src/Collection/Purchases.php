<?php

namespace CL\Purchases\Collection;

use Harp\Core\Repo\LinkMany;
use CL\Purchases\Model\ProductItem;
use CL\Purchases\Model\Store;
use CL\Purchases\Model\Purchase;


/**
 * @author     Ivan Kerin
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Purchases extends LinkMany
{
    public function addForStore(Store $store)
    {
        foreach ($this->get() as $purchase) {
            if ($purchase->getStore() === $store) {
                return $purchase;
            }
        }

        $purchase = new Purchase();
        $purchase->setStore($store);
        $this->add($purchase);

        return $purchase;
    }
}

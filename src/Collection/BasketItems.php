<?php

namespace CL\Purchases\Collection;

use Harp\Core\Repo\LinkMany;
use CL\Purchases\Model\Product;
use CL\Purchases\Model\ProductItem;
use CL\Purchases\Model\RefundItem;

/**
 * @author     Ivan Kerin
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class BasketItems extends LinkMany
{
    public function onlyProduct()
    {
        return $this->get()->filter(function ($item) {
            return $item instanceof ProductItem;
        });
    }

    public function onlyRefund()
    {
        return $this->get()->filter(function ($item) {
            return $item instanceof RefundItem;
        });
    }

    public function addForProduct(Product $product, $quantity = 1)
    {
        foreach ($this->onlyProduct() as $item) {
            if ($item->getProduct() === $product) {
                $item->quantity += $quantity;
                return $item;
            }
        }

        $item = new ProductItem(['quantity' => $quantity]);
        $item->setProduct($product);
        $this->add($item);

        return $item;
    }
}

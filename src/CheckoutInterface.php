<?php

namespace App;

interface CheckoutInterface
{
    /**
     * Add item to the checkout
     * 
     * @param $sku string
     * 
     */

    public function scan(string $sku);

    /**
     * Calculate the total amount of all items in this checkout
     *
     * @total 
     */

    public function total(): int;
}
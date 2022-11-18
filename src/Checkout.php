<?php

namespace App;

class Checkout implements CheckoutInterface
{
    /**
     * Define @var array
     */
    protected $cart = [];

    /**
     * @var int[]
     */
    protected $pricing = [
        'A' => 50,
        'B' => 30,
        'C' => 20,
        'D' => 15,
        'E' => 5
    ];

    /**
     * @var int[][]
     */
    protected $discounts = [
        'A' => [
            'item' => 3,
            'amount' => 130
        ],
        'B' => [
            'item' => 2,
            'amount' => 45
        ],
        'C' => [
            '0' => [
                'item' => 2,
                'amount' => 38
            ],
            '1' => [
                'item' => 3,
                'amount' => 50
            ]
        ],
        'D' => [
            'item' => 5,
            'amount' => '50'
        ]
    ];

    /**
     * @var int[]
     */
    protected $stats = [
        'A' => 0,
        'B' => 0,
        'C' => 0,
        'D' => 0,
        'E' => 0
    ];

    /**
     * Adds an item to the checkout
     *
     * @param $sku string
     */
    public function scan(string $sku)
    {
        if (!array_key_exists($sku, $this->pricing)) {
            return;
        }

        $this->stats[$sku] = $this->stats[$sku] + 1;

        $this->cart[] = [
            'sku' => $sku,
            'price' => $this->pricing[$sku]
        ];
    }

    /**
     * Calculates the total price of all items in this checkout
     *
     * @return int
     */
    public function total(): int
    {
        $standardPrices = array_reduce($this->cart, function ($total, array $product) {
            $total += $product['price'];
            return $total;
        }) ?? 0;

        $totalDiscount = 0;

        foreach ($this->discounts as $key => $discount) {
            if (isset($discount['item'])) {
                if ($this->stats[$key] >= $discount['item']) {
                    $numberOfSets = floor($this->stats[$key] / $discount['item']);
                    $totalDiscount += ($discount['amount'] * $numberOfSets);
                }
            }
            else {
                foreach ($discount as $dis) {
                    if ($this->stats[$key] >= $dis['item']) {
                        $numberOfSets = floor($this->stats[$key] / $dis['item']);
                        $totalDiscount += ($dis['amount'] * $numberOfSets);
                    }
                }
            }
        }

        return $standardPrices - $totalDiscount;
    }

}
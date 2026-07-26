<?php

return [

    // Points earned per currency unit (RM1) of a completed rental's total_amount, rounded down.
    'points_per_currency_unit' => 1,

    // Currency value of a single point when redeemed as a discount (RM per point).
    'redemption_rate' => 0.10,

    // Lifetime points required to reach each tier.
    'tiers' => [
        'bronze' => 0,
        'silver' => 500,
        'gold' => 2000,
    ],

];
